<?php

namespace App\Modules\Npds\Library;

use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Contracts\AuthInterface;
use App\Modules\Groupes\Support\Facades\Groupe;




class AuthManager implements AuthInterface 
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
     * [guard description]
     *
     * @param   [type]  $auth  [$auth description]
     *
     * @return  [type]         [return description]
     */
    public function guard($auth)
    {
        global $user, $admin;

        $guard = null;

        switch ($auth) {

            case 'user':
                $guard = (isset($user) ?: null);
                break;

            case 'admin':
                $guard = (isset($admin) ?: null);
                break;
        }

        return $guard;
    }

    /**
     * [check description]
     *
     * @param   [type]  $auth  [$auth description]
     *
     * @return  [type]         [return description]
     */
    public function check($auth)
    {
        global $user, $admin;

        $guard = null;

        switch ($auth) {

            case 'user':
                $guard = (isset($user) ? $user : null);
                break;

            case 'admin':
                $guard = (isset($admin) ? $admin : null);
                break;
        }

        return $guard;
    }

    /**
     * [secur_static description]
     *
     * @param   [type]  $sec_type  [$sec_type description]
     *
     * @return  [type]             [return description]
     */
    public function secur_static($sec_type)
    {
        $user   = Auth::guard('user');
        $admin  = Auth::guard('admin');

        switch ($sec_type) {

            case 'member':
                if (isset($user)) {
                    return true;
                } else {
                    return false;
                }
                break;

            case 'admin':
                if (isset($admin)) {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    /**
     * [autorisation description]
     *
     * @param   [type]  $auto  [$auto description]
     *
     * @return  [type]         [return description]
     */
    public function autorisation($auto)
    { 
        $user   = Auth::guard('user');
        $admin  = Auth::guard('admin');

        $affich = false;

        if (($auto == -1) and (!$user)) {
            $affich = true;
        }

        if (($auto == 1) and (isset($user))) {
            $affich = true;
        }

        if ($auto > 1) {
            $tab_groupe = Groupe::valid_group($user);

            if ($tab_groupe) {
                foreach ($tab_groupe as $groupevalue) {
                    if ($groupevalue == $auto) {
                        $affich = true;
                        break;
                    }
                }
            }
        }

        if ($auto == 0) {
            $affich = true;
        }

        if (($auto == -127) and ($admin)) {
            $affich = true;
        }

        return $affich;
    }

    /**
     * [is_admin description]
     *
     * @param   [type]  $xadmin  [$xadmin description]
     *
     * @return  [type]           [return description]
     */
    public function is_admin($xadmin)
    {
        $admin  = Auth::guard('admin');

        if (isset($admin) and ($admin != '')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * [is_user description]
     *
     * @param   [type]  $xuser  [$xuser description]
     *
     * @return  [type]          [return description]
     */
    public function is_user($xuser)
    {
        $user   = Auth::guard('user');

        if (isset($user) and ($user != '')) {
            return true;
        } else {
            return false;
        }
    }

}
