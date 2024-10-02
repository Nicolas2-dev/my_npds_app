<?php

namespace App\Modules\Users\Controllers\Front;

use Npds\view\View;
use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Session\Session;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Users\Library\Traits\UserLogoutTrait;

/**
 * [UserLogin description]
 */
class UserLogin extends FrontController
{

    use UserLogoutTrait;

    /**
     * [$pdst description]
     *
     * @var [type]
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
     * [login description]
     *
     * @return  [type]  [return description]
     */
    public function login()
    {
        $this->title(__('user login'));

        $this->set('stop', Request::query('stop'));

        if (View::exists('Modules/Users/Views/Partials/user')) {
            $this->set('user_include', View::make('Modules/Users/Views/Partials/user')->fetch());
        } 
    }

    /**
     * [submit description]
     *
     * @return  [type]  [return description]
     */
    public function submit()
    {
        $uname  = (Request::query('uname') ?: Request::post('uname'));
        $pass   = (Request::query('pass') ?: Request::post('pass'));

        if ($setinfo = DB::table('users')
                        ->select('pass', 'hashkey', 'uid', 'uname', 'storynum', 'umode', 'uorder', 'thold', 'noscore', 'ublockon', 'theme', 'commentmax', 'user_langue')
                        ->where('uname', $uname)
                        ->first()) {
    
            $user_status = DB::table('users_status')->select('open')->where('uid', $setinfo['uid'])->first();

            // user compte non activé
            if ($user_status['open'] == 0) {
                return Url::redirect('user/login?stop=99');
            }

            // verify password or update password new crypt
            if ($setinfo['hashkey'] == 1) {
                $CryptpPWD = Password::npds_verify_password($setinfo, $pass);
            } else  {
                $CryptpPWD = Password::npds_update_news_crypt_password($pass, $setinfo, $uname);                
            }

            // creation du cookie
            Cookie::docookie(
                $setinfo['uid'], 
                $setinfo['uname'], 
                $CryptpPWD, 
                $setinfo['storynum'], 
                $setinfo['umode'], 
                $setinfo['uorder'], 
                $setinfo['thold'], 
                $setinfo['noscore'], 
                $setinfo['ublockon'], 
                $setinfo['theme'], 
                $setinfo['commentmax'], 
                $setinfo['user_langue']
            );
    
            $ip = Request::getip();

            if (DB::table('session')->where('host_addr', $ip)->where('guest', 1)->count() == 1) {
                DB::table('session')->where('host_addr', $ip)->where('guest', 1)->delete();
            }
    
            Session::set('message', ['type' => 'warning', 'text' => __d('users', 'Vous ete maintenant conecter a votre compte.')]);

            return Url::redirect('index');
        } else {
            return Url::redirect('user/login?stop=1');
        }
    }

}
