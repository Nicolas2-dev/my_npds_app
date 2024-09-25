<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Cookie;

/**
 * [UserLogin description]
 */
class UserLogin extends FrontController
{

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

        //     case 'logout':
        //         logout();
        //         break;
        
        //     case 'login':
        //         login($uname, $pass);
        //         break;                
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
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        $stop = Request::query('stop');

        $this->title(__('user login'));

        $this->set('stop', $stop);

        // include externe file from themes/default/include for functions, codes ...
        // if (file_exists("themes/default/include/user.inc"))
        //      include("themes/default/include/user.inc");

    }

    /**
     * [submit description]
     *
     * @return  [type]  [return description]
     */
    public function submit()
    {
        
    
        $uname = Request::post('uname');
        $pass = Request::post('pass');

        $result = sql_query("SELECT pass, hashkey, uid, uname, storynum, umode, uorder, thold, noscore, ublockon, theme, commentmax, user_langue FROM users WHERE uname = '$uname'");
        
        if (sql_num_rows($result) == 1) {
    
            $setinfo = sql_fetch_assoc($result);
    
            $result = sql_query("SELECT open FROM users_status WHERE uid='" . $setinfo['uid'] . "'");
            list($open_user) = sql_fetch_row($result);
    
            if ($open_user == 0) {
                return Url::redirect('user/login?stop=99');
            }
    
            $dbpass = $setinfo['pass'];
    
            if (password_verify($pass, $dbpass) or (strcmp($dbpass, $pass) == 0)) {
                if (!$setinfo['hashkey']) {
                    $AlgoCrypt  = PASSWORD_BCRYPT;
                    $min_ms     = 100;
                    $options    = ['cost' => getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms)];
                    $hashpass   = password_hash($pass, $AlgoCrypt, $options);
                    $pass       = crypt($pass, $hashpass);
    
                    sql_query("UPDATE users SET pass='$pass', hashkey='1' WHERE uname='$uname'");
    
                    $result = sql_query("SELECT pass, hashkey, uid, uname, storynum, umode, uorder, thold, noscore, ublockon, theme, commentmax, user_langue FROM users WHERE uname = '$uname'");
                    
                    if (sql_num_rows($result) == 1) {
                        $setinfo = sql_fetch_assoc($result);
                    }
    
                    $dbpass = $setinfo['pass'];
                    $scryptPass = crypt($dbpass, $hashpass);
                }
            } else {
                $scryptPass = '';
            }
    
            if (password_verify(urldecode($pass), $dbpass) or password_verify($pass, $dbpass)) {
                $CryptpPWD = $dbpass;
            } elseif (password_verify($dbpass, $scryptPass) or strcmp($dbpass, $pass) == 0) {
                $CryptpPWD = $pass;
            } else {
                return Url::redirect('user/login?stop=1');
            }
    
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

            $result = DB::table('session')->where('host_addr', $ip)->where('guest', 1)->count();

            if ($result == 1) {
                DB::table('session')->where('host_addr', $ip)->where('guest', 1)->delete();
            }
    
            return Url::redirect('index');
        } else {
            return Url::redirect('user/login?stop=1');
        }
    }

    /**
     * [logout description]
     *
     * @return  [type]  [return description]
     */
    public function logout()
    {
        global $user, $user_language, $cookie;
    
        if ($cookie[1] != '') {
            DB::table('session')->where('username', $cookie[1])->delete();
        }
    
        Cookie::set('user', '', 0);
        unset($user);
    
        Cookie::set('user_language', '', 0);
        unset($user_language);
    
        return Url::redirect('index');
    }    

}