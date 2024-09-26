<?php

namespace App\Modules\Npds\Library;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Cookie\Cookie;
use App\Modules\Npds\Contracts\CookieInterface;


class CookieManager extends Cookie implements CookieInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private $user_cookie = null;


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
     * [cookiedecode description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function cookiedecode($user)
    {
        $stop = false;

        if (array_key_exists("user", Request::query())) {
            if (Request::query('user') != '') {
                $stop = true;
                $user = "BAD-GET";
            }
        } 

        if ($user) {
            $cookie = $this->explode($user);

            settype($cookie[0], "integer");

            if (trim($cookie[1]) != '') {

                $result = sql_query("SELECT pass, user_langue 
                                    FROM users 
                                    WHERE uname='$cookie[1]'");

                if (sql_num_rows($result) == 1) {

                    list($pass, $user_langue) = sql_fetch_row($result);

                    if (($cookie[2] == md5($pass)) and ($pass != '')) {
                        if (Config::get('npds.language') != $user_langue) {
                            sql_query("UPDATE users 
                                    SET user_langue='".Config::get('npds.language')."' 
                                    WHERE uname='$cookie[1]'");
                        }

                        $this->user_cookie = $cookie;

                        return $cookie;
                    } else {
                        $stop = true;
                    }
                } else {
                    $stop = true;
                }
            } else {
                $stop = true;
            }

            if ($stop) {
                static::set('user', '', 0);
                
                unset($user);
                unset($cookie);

                header("Location: ". site_url('index.php'));
            }
        }
    }

    /**
     * [explode description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function explode($user)
    {
        return explode(':', base64_decode($user));
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function cookie_get_user($id)
    {
        global $user;

        $cookie = $this->cookiedecode($user);
        
        return $cookie[$id]; 
    }

    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function cookie_user($id = '')
    {
        if (!empty($id)) {
            return $this->user_cookie[$id];
        } else {
            return $this->user_cookie;
        }
    }


    /**
     * [docookie description]
     *
     * @param   [type]  $setuid         [$setuid description]
     * @param   [type]  $setuname       [$setuname description]
     * @param   [type]  $setpass        [$setpass description]
     * @param   [type]  $setstorynum    [$setstorynum description]
     * @param   [type]  $setumode       [$setumode description]
     * @param   [type]  $setuorder      [$setuorder description]
     * @param   [type]  $setthold       [$setthold description]
     * @param   [type]  $setnoscore     [$setnoscore description]
     * @param   [type]  $setublockon    [$setublockon description]
     * @param   [type]  $settheme       [$settheme description]
     * @param   [type]  $setcommentmax  [$setcommentmax description]
     * @param   [type]  $user_langue    [$user_langue description]
     *
     * @return  [type]                  [return description]
     */
    function docookie($setuid, $setuname, $setpass, $setstorynum, $setumode, $setuorder, $setthold, $setnoscore, $setublockon, $settheme, $setcommentmax, $user_langue)
    {
        $info = base64_encode("$setuid:$setuname:" . md5($setpass) . ":$setstorynum:$setumode:$setuorder:$setthold:$setnoscore:$setublockon:$settheme:$setcommentmax");
        
        $user_cook_duration = Config::get('npds.user_cook_duration');
    
        if ($user_cook_duration <= 0) {
            $user_cook_duration = 1;
        }
    
        $timeX = time() + (3600 * $user_cook_duration);
    
        static::set("user", "$info", $timeX);
    
        if ($user_langue != '') {
            static::set('user_language', "$user_langue", $timeX);
        }
    }

}
