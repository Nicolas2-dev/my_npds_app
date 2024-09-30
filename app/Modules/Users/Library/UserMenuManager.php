<?php

namespace App\Modules\Users\Library;


use Npds\view\View;
use Npds\Http\Request;
use Npds\Config\Config;
use App\Modules\Users\Contracts\UserMenuInterface;

/**
 * Undocumented class
 */
class UserMenuManager implements UserMenuInterface 
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
     * @param [type] $userinfo
     * @return void
     */
    public function member($userinfo)
    {
        return View::make('Modules/Users/Views/Partials/Menu/user_menu', [
            'userinfo'  => $userinfo,
            'ed_u'      => $this->check('edituser'),
            'cl_edj'    => $this->check('editjournal'),
            'cl_edh'    => $this->check('edithome'),
            'cl_cht'    => $this->check('chgtheme'),
            'cl_u'      => $this->check('dashboard'),
            'cl_pm'     => $this->check('viewpmsg'),
            'cl_rs'     => $this->check('sociaux'), 
        ]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $uri
     * @return void
     */
    private function check($uri)
    {
        return ((Request::post('op') ?: Request::query('op')) == $uri ? 'active' : '');
    }

}
