<?php

namespace Modules\Authors\Controllers\Front;

use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Cookie;

/**
 * [UserLogin description]
 */
class AuthorLogin extends FrontController
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
    public function login()
    {
        $this->title(__d('authors', 'Administration login'));

        $this->set('adminfoot', Css::adminfoot('fv', '', 'var formulid = ["adminlogin"];', ''));
    }

    /**
     * [submit description]
     *
     * @return  [type]  [return description]
     */
    public function submit()
    {
        $aid    = Request::post('aid');
        $pwd    = Request::post('pwd');
        $op     = Request::post('op');

        if ((isset($aid)) and (isset($pwd)) and ($op == 'login')) {
            if ($aid != '' and $pwd != '') {
        
                if ($setinfo = DB::table('authors')->select('pwd', 'hashkey')->where('aid', $aid)->first()) {
        
                    $dbpass     = $setinfo['pwd'];
                    $scryptPass = null;
        
                    if (password_verify($pwd, $dbpass) or (strcmp($dbpass, $pwd) == 0)) {
                        if (!$setinfo['hashkey']) {

                            $AlgoCrypt  = PASSWORD_BCRYPT;
                            $min_ms     = 100;
                            $options    = ['cost' => getOptimalBcryptCostParameter($pwd, $AlgoCrypt, $min_ms)];
                            $hashpass   = password_hash($pwd, $AlgoCrypt, $options);
                            $pwd        = crypt($pwd, $hashpass);
        
                            DB::table('authors')->where('aid', $aid)->update(
                                [
                                    'pwd'       => $pwd,
                                    'hashkey'   => 1,
                                ]
                            );

                            if ($setinfo = DB::table('authors')->select('pwd', 'hashkey')->where('aid', $aid)->first()) {
                                $dbpass     = $setinfo['pwd'];
                                $scryptPass = crypt($dbpass, $hashpass);
                            }
                        }
                    }
        
                    if (password_verify($pwd, $dbpass)) {
                        $CryptpPWD = $dbpass;
                    } elseif (password_verify($dbpass, $scryptPass) or strcmp($dbpass, $pwd) == 0) {
                        $CryptpPWD = $pwd;
                    } else {
                        Admin_Alert("Passwd not in DB#1 : $aid");
                    }
        
                    $admin = base64_encode("$aid:" . md5($CryptpPWD));
        
                    $admin_cook_duration = Config::get('npds.admin_cook_duration');

                    if ($admin_cook_duration <= 0) {
                        $admin_cook_duration = 1;
                    }
        
                    $timeX = time() + (3600 * $admin_cook_duration);
        
                    Cookie::set('admin', $admin, $timeX);
                    Cookie::set('adm_exp', $timeX, $timeX);

                    Url::redirect('admin');
                }
            } else {
                Url::redirect('admin/login');
            }
        } else {
            Url::redirect('admin/login');
        }
    }   

}
