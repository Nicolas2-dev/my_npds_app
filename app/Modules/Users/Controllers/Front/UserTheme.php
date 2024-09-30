<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Console\Console;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use Npds\Supercache\SuperCacheManager;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Support\Facades\UserMenu;

/**
 * [UserLogin description]
 */
class UserTheme extends FrontController
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
        Console::log('Controller : '. __CLASS__);

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

        Console::logSpeed('START Controller : '. __CLASS__);

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * [chgtheme description]
     *
     * @return  [type]  [return description]
     */
    public function chgt_heme()
    {    
        $this->title(__('Editer votre page principale'));
            
        $this->set('message', Session::message('message'));

        if (Auth::guard('user')) {
            $userinfo = User::getuserinfo(Auth::check('user'));
            
            $this->set('user_menu', UserMenu::member($userinfo));
        
            $this->set('userinfo', $userinfo);

            $theme = explode('+', $userinfo['theme']);

            // select theme
            $this->set('themelist', Theme::lists('Frontend'));
            $this->set('theme', $theme[0]);

            // select skin
            $this->set('skins', Theme::skin_lists());
            $this->set('skin', array_key_exists(1, $theme) ? $theme[1] : '');
        } else {
            Url::redirect('index'); 
        }           
    }
    
    /**
     * [savetheme description]
     *
     * @param   [type]  $uid    [$uid description]
     * @param   [type]  $theme  [$theme description]
     *
     * @return  [type]          [return description]
     */
    public function save_theme()
    {
        $cookie = Cookie::cookiedecode(Auth::check('user'));
    
        $user_temp = DB::table('users')->select('uid')->where('uname', $cookie[1])->first();

        $input = Request::post();

        if ($input['uid'] == $user_temp['uid']) {

            DB::table('users')->where('uid', $input['uid'])->update([
                'theme' => $theme = substr($input['theme_local'], -3) != "_sk" ? $input['theme_local'] : $input['theme_local'] . "+" . $input['skins']
            ]);

            $userinfo = User::getuserinfo(Auth::check('user'));
    
            Cookie::docookie(
                $userinfo['uid'], 
                $userinfo['uname'], 
                $userinfo['pass'], 
                $userinfo['storynum'], 
                $userinfo['umode'], 
                $userinfo['uorder'], 
                $userinfo['thold'], 
                $userinfo['noscore'], 
                $userinfo['ublockon'], 
                $theme, 
                $userinfo['commentmax'], 
                ''
            );
            
            // Include cache manager for purge cache Page
            $cache_obj = new SuperCacheManager();
            $cache_obj->UsercacheCleanup();
    
            Session::set('message', ['type' => 'success', 'text' => __d('users', 'Votre theme et skin ont été mis à jour avec success.')]);

            Url::redirect('user');
        } else {
            Url::redirect('index');
        }
    }

}
