<?php

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Support\AlertNpds;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;


/**
 * Bloc Admin
 * 
 * syntaxe : function#adminblock
 *
 * @return  [type]  [return description]
 */
function adminblock()
{
    if (Auth::guard('admin')) {

        $admin = Auth::check('admin');

        $Xadmin = base64_decode($admin);
        $Xadmin = explode(':', $Xadmin);

        $aid = urlencode($Xadmin[0]);

        $Q = DB::table('authors')->select('*')->where('aid', $aid)->first();

        $R = ($Q['radminsuper'] == 1 
            ? DB::table('fonctions')
                    ->select('*')
                    ->where('finterface', 1)
                    ->where('fetat', '!=', 0)
                    ->orderBy('fcategorie')
                    ->get()

            : DB::table('fonctions')->select('*')
                ->leftJoin('droits', 'fonctions.fdroits1', '=', 'droits.d_fon_fid')
                ->leftJoin('authors', 'droits.d_aut_aid', '=', 'authors.aid')
                ->where('fonctions.finterface', 1)
                ->where('fonctions.fetat', '!=', 0)
                ->where('droits.d_aut_aid', $aid)
                ->where('droits.d_droits', 'REGEXP', '^1')
                ->orderBy('fonctions.fcategorie')
                ->get()
        );

        $bloc_foncts_A  = '';
        $adminico       = '';

        foreach ($R as $SAQ) {

            $cat[]      = $SAQ['fcategorie'];
            $cat_n[]    = $SAQ['fcategorie_nom'];
            $fid_ar[]   = $SAQ['fid'];

            if ($SAQ['fcategorie'] == 9) {
                $adminico = Config::get('npds.adminimg') . $SAQ['ficone'] . '.' . Config::get('npds.admf_ext');
            }

            // Note url 'op=Extend-Admin-SubModule' a revoir 
            if ($SAQ['fcategorie'] == 9 and strstr($SAQ['furlscript'], "op=Extend-Admin-SubModule")) {
                
                if (file_exists(module_path($SAQ['fnom'] . '/' . $SAQ['fnom'] . '.' . Config::get('npds.admf_ext')))) {
                    $adminico = 'modules/' . $SAQ['fnom'] . '/' . $SAQ['fnom'] . '.' . Config::get('npds.admf_ext');
                } else {
                    $adminico = Config::get('npds.adminimg') . 'module.' . Config::get('npds.admf_ext');
                }
            }

            $bloc_foncts_A .= AlertNpds::check($SAQ, $adminico, $aid, $Q['radminsuper']);
        }

        $block = DB::table('block')->select('title', 'content')->find(2);

        global $block_title;
        $title = $block['title'] == '' ? $block_title : aff_langue($block['title']);

        Theme::themesidebox($title, View::make('Modules/Authors/Views/Boxe/AdminBlock', 
            [
                //
                'content'       => Language::aff_langue(
                                        preg_replace_callback(
                                            '#<a href=[^>]*(&)[^>]*>#', 
                                            [Sanitize::class, 'changetoampadm'], 
                                            $block['content']
                                        )
                                    ),
                //
                'radminsuper'   => $Q['radminsuper'],

                // 
                'bloc_foncts_A' => $bloc_foncts_A,

                //
                'alert_npds'    => AlertNpds::display(),

                //
                'aid'           => $aid
            ]
        ));
    }
}