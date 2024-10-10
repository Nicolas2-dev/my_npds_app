<?php

namespace Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Session\Session;
use Modules\Users\Models\User;
use Modules\Npds\Support\Sanitize;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Hack;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Users\Support\Facades\UserMenu;
use Modules\Users\Support\Facades\User as H_User;
use Modules\Users\Validator\ValidatorUserEditeHome;

/**
 * [UserLogin description]
 */
class UserHome extends FrontController
{

    /**
     * Undocumented variable
     *
     * @var integer
     */
    protected $pdst = 0;

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
    public function edit_home()
    {
        if (Auth::guard('user')) {
            
            $this->title(__('Editer votre page principale'));
            
            $this->set('message', Session::message('message'));

            $userinfo = H_User::getuserinfo(Auth::check('user'));
        
            if (empty($userinfo['theme'])) {
                $userinfo['theme'] = (Config::get('npds.Default_Theme') .'+'. Config::get('npds.Default_Skin'));
            }

            with(new ValidatorUserEditeHome())->display();

            $this->set('userinfo',  $userinfo);
            $this->set('user_menu', UserMenu::member($userinfo));

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
    public function save_home()
    {
        $userinfo = User::find(Request::post('uid'));

        $cookie = Cookie::cookiedecode(Auth::check('user'));

        if ((Request::post('uname') == $cookie[1]) 
        and (Request::post('uid')   == $userinfo->uid) 
        and (Request::post('op')    == 'savehome')
        and (Request::isPost())) 
        {
            $userinfo->storynum = Request::post('storynum');
            $userinfo->ublockon = (Request::post('ublockon') ? 1 : 0);
            $userinfo->ublock   = Hack::remove(Sanitize::FixQuotes(Request::post('ublock')));
            $userinfo->save();

            Cookie::docookie(
                $userinfo->uid, 
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
    
            Session::set('message', ['type' => 'success', 'text' => __d('users', 'Votre home a été sauvegarder avec success.')]);

            Url::redirect('user?op=dashboard#message');
        } else {
            Url::redirect('index');
        }
    }

}
