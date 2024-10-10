<?php

namespace Modules\ReseauxSociaux\Library;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Auth;
use Modules\Users\Support\Facades\User;
use Modules\ReseauxSociaux\Contracts\ReseauxInterface;

/**
 * Undocumented class
 */
class ReseauxManager implements ReseauxInterface 
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
     * Undocumented function
     *
     * @param [type] $posterdata_extend
     * @return void
     */
    public function reseaux_list($posterdata_extend)
    {
        if (Auth::guard('user') or Auth::autorisation(-127)) {
            if ($posterdata_extend['M2'] != '') {

                $socialnetworks     = [];
                $socialnetworks     = explode(';', $posterdata_extend['M2']);

                $res_id             = [];

                foreach ($socialnetworks as $socialnetwork) {
                    $res_id[] = explode('|', $socialnetwork);
                }

                sort($res_id);

                $rs = Config::get('reseauxsociaux.config.rs');
                sort($rs);

                $my_rs = '';

                foreach ($rs as $v1) {

                    foreach ($res_id as $y1) {
                        $k = array_search($y1[0], $v1);

                        if (false !== $k) {
                            $my_rs .= '<a class="me-2 " href="';

                            if ($v1[2] == 'skype') {
                                $my_rs .= $v1[1] . $y1[1] . '?chat';
                            } else {
                                $my_rs .= $v1[1] . $y1[1];
                            }

                            $my_rs .= '" target="_blank"><i class="fab fa-' . $v1[2] . ' fa-lg fa-fw mb-2"></i></a> ';
                            break;
                        } else {
                            $my_rs .= '';
                        }
                    }
                }

                return $my_rs;
            }
        }
    }

}
