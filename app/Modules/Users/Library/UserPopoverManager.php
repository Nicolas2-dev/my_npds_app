<?php

namespace App\Modules\Users\Library;


use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Spam;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Support\Facades\Avatar;
use App\Modules\Users\Contracts\UserPopoverInterface;
use App\Modules\ReseauxSociaux\Support\Facades\Reseaux;

/**
 * Undocumented class
 */
class UserPopoverManager implements UserPopoverInterface 
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
     * à partir du nom de l'utilisateur ($who)
     * 
     * @param [type] $who
     * @param [type] $dim 
     * @param [type] $avpop
     * @return void
     */
    public function userpopover($who, $dim, $ldim, $avpop)
    {
        if (DB::table('users')->select('uname')->find($who, 'uname')) {
    
            $temp_user = User::get_userdata($who);

            if (!$avpop) {
                if (!Config::get('npds.short_user')) {
                    if ($temp_user['uid'] != 1) {
                        //
                        $my_rs = Reseaux::reseaux_list($temp_user);
                    }
                }

                $useroutils = '';
        
                if (Auth::guard('user') or Auth::autorisation(-127)) {
                    if ($temp_user['uid'] != 1 and $temp_user['uid'] != '') {
                        $useroutils .= '<li>
                        <a class="dropdown-item text-center text-md-start" href="'. site_url('user?op=userinfo&amp;uname=' . $temp_user['uname']) . '" target="_blank" title="' . __d('users', 'Profil') . '" >
                            <i class="fa fa-lg fa-user align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                ' . __d('users', 'Profil') . '
                            </span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-center text-md-start" href="'. site_url('messenger?op=instant_message&amp;to_userid=' . urlencode($temp_user['uname'])) . '" title="' . __d('users', 'Envoyer un message interne') . '" >
                                <i class="far fa-lg fa-envelope align-middle fa-fw"></i>
                                <span class="ms-2 d-none d-md-inline">
                                    ' . __d('users', 'Message') . '
                                </span>
                            </a>
                        </li>';
                    }

                    if ($temp_user['femail'] != '') {
                        $useroutils .= '<li>
                            <a class="dropdown-item  text-center text-md-start" href="mailto:' . Spam::anti_spam($temp_user['femail'], 1) . '" target="_blank" title="' . __d('users', 'Email') . '" >
                                <i class="fa fa-at fa-lg align-middle fa-fw"></i>
                                <span class="ms-2 d-none d-md-inline">
                                    ' . __d('users', 'Email') . '
                                </span>
                            </a>
                        </li>';
                    }

                    if ($temp_user['uid'] != 1 and isset($posterdata_extend) and array_key_exists($ch_lat = Config::get('geoloc.config.ch_lat'), $posterdata_extend)) {
                        if ($posterdata_extend[$ch_lat] != '') {
                            $useroutils .= '<li>
                                <a class="dropdown-item text-center text-md-start" href="'. site_url('geoloc?op=u' . $temp_user['uid']) . '" title="' . __d('users', 'Localisation') . '" >
                                    <i class="fas fa-map-marker-alt fa-lg align-middle fa-fw">&nbsp;</i>
                                    <span class="ms-2 d-none d-md-inline">
                                        ' . __d('users', 'Localisation') . '
                                    </span>
                                </a>
                            </li>';
                        }
                    }
                }
        
                if ($temp_user['url'] != '') {
                    $useroutils .= '<li>
                        <a class="dropdown-item text-center text-md-start" href="' . $temp_user['url'] . '" target="_blank" title="' . __d('users', 'Visiter ce site web') . '">
                            <i class="fas fa-external-link-alt fa-lg align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                ' . __d('users', 'Visiter ce site web') . '
                            </span>
                        </a>
                    </li>';
                }

                if ($temp_user['mns']) {
                    $useroutils .= '<li>
                        <a class="dropdown-item text-center text-md-start" href="' . site_url('minisite.php?op=' . $temp_user['uname']) . '" target="_blank" target="_blank" title="' . __d('users', 'Visitez le minisite') . '" >
                            <i class="fa fa-lg fa-desktop align-middle fa-fw"></i>
                            <span class="ms-2 d-none d-md-inline">
                                ' . __d('users', 'Visitez le minisite') . '
                            </span>
                        </a>
                    </li>';
                }
            }

            $avatar_url = Avatar::url($temp_user);
 
            return $avpop 
                ?   '<img class="btn-outline-primary img-thumbnail img-fluid n-ava-' . $dim . ' me-2" src="' . $avatar_url . '" alt="' . $temp_user['uname'] . '" loading="lazy" />' 
                :   '<div class="dropdown d-inline-block me-4 dropend">
                        <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <img class=" btn-outline-primary img-fluid n-ava-' . $dim . ' me-0" src="' . $avatar_url . '" alt="' . $temp_user['uname'] . '" />
                        </a>
                        <ul class="dropdown-menu bg-light">
                            <li><span class="dropdown-item-text text-center py-0 my-0">
                                <img class="btn-outline-primary img-thumbnail img-fluid n-ava-' . $ldim . ' me-2" src="' . $avatar_url . '" alt="' . $temp_user['uname'] . '" loading="lazy" /></span>
                            </li>
                            <li><h6 class="dropdown-header text-center py-0 my-0">' . $who . '</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            ' . $useroutils . '
                          
                            '. (isset($my_rs) 
                                ?  '<li><hr class="dropdown-divider"></li>
                                    <li><div class="mx-auto text-center" style="max-width:170px;">'. $my_rs .'</div>' 
                                : ''
                            ). '
                        </ul>
                    </div>';
        }
    }

}
