<?php

namespace Modules\Users\Library;

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Npds\Support\Facades\Auth;
use Modules\Users\Support\Facades\User;
use Modules\Users\Support\Facades\Avatar;
use Modules\Users\Contracts\UserPopoverInterface;
use Modules\ReseauxSociaux\Support\Facades\Reseaux;

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
    public function userpopover($who, $dim, $avpop, $ldim ='')
    {
        if (DB::table('users')->select('uname')->find($who, 'uname')) {
    
            $temp_user = User::get_userdata($who);

            $temp_user['uname'] = urlencode($temp_user['uname']);

            if (!$avpop) {
                
                $posterdata_extend = User::get_userdata_extend_from_id($temp_user['uid']);
                
                if (Config::get('reseauxsociaux.config.reseaux_actif') and $temp_user['uid'] != 1) {
                    $user_reseaux = Reseaux::reseaux_list($posterdata_extend);
                }

                if (Auth::guard('user') or Auth::autorisation(-127)) {
                    $guard = true;
                    
                    if ($temp_user['uid'] != 1 and isset($posterdata_extend) and array_key_exists($ch_lat = Config::get('geoloc.config.ch_lat'), $posterdata_extend)) {
                        if ($posterdata_extend[$ch_lat] != '') {
                            $geoloc = true;
                        }
                    }
                }
            }

            return View::make('Modules/Users/Views/Partials/user_popover', [
                // guard
                'guard'         => (isset($guard) ? $guard : false),

                // geoloc
                'geoloc'        => (isset($geoloc) ? $geoloc : false),

                // variables
                'avpop'         => $avpop,
                'dim'           => $dim,
                'ldim'          => (empty($ldim) ? $dim : $ldim),
                'who'           => $who,

                // user info
                'user'          => $temp_user,
                'avatar_url'    => Avatar::url($temp_user),

                // reseaux sociaux
                'user_reseaux'  => (isset($user_reseaux) ? $user_reseaux : false),
            ]);        
        }
    }

}
