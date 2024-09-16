<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use App\Modules\Users\Models\User;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Users\Support\Facades\User as H_User;
use App\Modules\Npds\Library\Supercache\SuperCacheManager;


/**
 * [UserLogin description]
 */
class UserHome extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * [edithome description]
     *
     * @return  [type]  [return description]
     */
    public function edithome()
    {
        global $user;

        if ($user) {
            
            $this->title(__('Editer votre page principale'));
            
            $userinfo = H_User::getusrinfo($user);
        
            if ($userinfo['theme'] == '') {
                $userinfo['theme'] = Config::get('npds.Default_Theme') + Config::get('npds.Default_Skin');
            }

            $fv_parametres = '
            storynum: {
                validators: {
                    regexp: {
                        regexp:/^[1-9](\d{0,2})$/,
                        message: "0-9"
                    },
                    between: {
                        min: 1,
                        max: 127,
                        message: "1 ... 127"
                    }
                }
            },';
        
            $arg1 = 'var formulid=["changehome"];';

            $this->set('userinfo',  $userinfo);
            $this->set('adminfoot', Css::adminfoot('fv', $fv_parametres, $arg1, 'foo'));
            $this->set('user_menu', H_User::member_menu($userinfo['mns'], $userinfo['uname']));

        } else {
            Url::redirect('index');
        }
    }
    
    /**
     * [savehome description]
     *
     * @param   [type]  $uid       [$uid description]
     * @param   [type]  $uname     [$uname description]
     * @param   [type]  $theme     [$theme description]
     * @param   [type]  $storynum  [$storynum description]
     * @param   [type]  $ublockon  [$ublockon description]
     * @param   [type]  $ublock    [$ublock description]
     *
     * @return  [type]             [return description]
     */
    public function savehome()
    {
        $userinfo = User::find(Request::post('id'));

        if ((Request::post('uname') == Cookie::cookie_get_user(1)) 
        and (Request::post('id')    == $userinfo->id) 
        and (Request::post('op')    == 'savehome')) 
        {
            $userinfo->storynum = Request::post('storynum');
            $userinfo->ublockon = (Request::post('ublockon') ? 1 : 0);
            $userinfo->ublock   = Hack::remove(Sanitize::FixQuotes(Request::post('ublock')));
            $userinfo->save();

            Cookie::docookie(
                $userinfo->id, 
                $userinfo->uname, 
                $userinfo->pass, 
                $userinfo->storynum, 
                $userinfo->umode, 
                $userinfo->uorder, 
                $userinfo->thold, 
                $userinfo->noscore, 
                $userinfo->ublockon, 
                $userinfo->theme, 
                $userinfo->commentmax, 
                ''
            );
            
            // Include cache manager for purge cache Page
            $cache_obj = new SuperCacheManager();
            $cache_obj->UsercacheCleanup();
    
            Url::redirect('user/home');
        } else {
            Url::redirect('index');
        }
    }

}
