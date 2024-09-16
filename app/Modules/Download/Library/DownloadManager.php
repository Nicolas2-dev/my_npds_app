<?php

namespace App\Modules\Download\Library;

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Download\Contracts\DownloadInterface;


class DownloadManager implements DownloadInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [topdownload_data description]
     *
     * @param   [type]  $form   [$form description]
     * @param   [type]  $ordre  [$ordre description]
     *
     * @return  [type]          [return description]
     */
    public function topdownload_data($form, $ordre)
    {
        global $long_chain;
    
        if (!$long_chain) {
            $long_chain = 13;
        }

        $lugar = 1;
        $download_data = '';

        foreach (DB::table('downloads')
                    ->select('did', 'dcounter', 'dfilename', 'dcategory', 'ddate', 'perms')
                    ->orderBy($ordre, 'desc')
                    ->offset(Config::get('npds.top'))
                    ->limit(0)
                    ->get() as $download) 
        {
            if ($download['dcounter'] > 0) {
                $okfile = Auth::autorisation($download['dperm']);
    
                if ($ordre == 'dcounter') {
                    $download_date = Sanitize::wrh($download['dcounter']);
                }
    
                if ($ordre == 'ddate') {
                    $download_date = str_replace(
                        [
                            'd', 'm', 'Y', 'H:i'
                        ],
                        [
                            substr($download['ddate'], 8, 2), substr($download['ddate'], 5, 2), substr($download['ddate'], 0, 4), ''
                        ],
                        'd-m-Y H:i'
                    );
                }
    
                $ori_dfilename = $download['dfilename'];
    
                if (strlen($download['dfilename']) > $long_chain) {
                    $dfilename = (substr($download['dfilename'], 0, $long_chain)) . " ...";
                }
    
                if ($form == 'short') {
                    if ($okfile) {
                        $download_data[] = [
                            'lugar'         => $lugar,
                            'id'            => $download['did'],
                            'filename'      => $dfilename,
                            'ori_dfilename' => $ori_dfilename,
                            'download_date' => $download_date,
                        ];
                    }
                } else {
                    if ($okfile) {
                        $download_data[] = [
                            'filename'     => $dfilename,
                            'id'           => $download['did'],
                            'category'     => Language::aff_langue(stripslashes($download['dcategory'])),
                            'counter'      => Sanitize::wrh($download['dcounter']),
                        ];
                    }
                }
    
                if ($okfile) {
                    $lugar++;
                }
            }
        }

        return View::make('Modules/Download/Views/Partials/Top_Download_Data', compact('download_data', 'ordre', 'form'));
    }

}
