<?php

namespace App\Modules\Npds\Library;

use Npds\Http\Request;
use Npds\Config\Config;
use App\Modules\Npds\Contracts\SessionInterface;


class SessionManager implements SessionInterface
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
     * [session_manage description]
     *
     * @return  [type]  [return description]
     */
    public function session_manage()
    {
        global $cookie, $REQUEST_URI;

        $guest = 0;
        $ip = Request::getip();

        $username = isset($cookie[1]) ? $cookie[1] : $ip;

        if ($username == $ip) {
            $guest = 1;
        }

        if (Config::has('geoloc.config.geo_ip') && Config::get('geoloc.config.geo_ip') == 1) {
            include module_path('geoloc/geoloc_refip.php');
        }

        //<== geoloc
        $past = time() - 300;

        sql_query("DELETE FROM session WHERE time < '$past'");

        $result = sql_query("SELECT time FROM session WHERE username='$username'");

        if ($row = sql_fetch_assoc($result)) {
            if ($row['time'] < (time() - 30)) {

                sql_query("UPDATE session 
                        SET username='$username', 
                            time='" . time() . "', 
                            host_addr='$ip', 
                            guest='$guest', 
                            uri='$REQUEST_URI', 
                            agent='" . getenv("HTTP_USER_AGENT") . "' 
                            WHERE username='$username'");

                if ($guest == 0) {

                    sql_query("UPDATE users 
                            SET user_lastvisit='" . (time() + (int) Config::get('npds.gmt') * 3600) . "' 
                            WHERE uname='$username'");
                }
            }
        } else {
            sql_query("INSERT INTO session (username, time, host_addr, guest, uri, agent) 
                    VALUES ('$username', '" . time() . "', '$ip', '$guest', '$REQUEST_URI', '" . getenv("HTTP_USER_AGENT") . "')");
        }
    }
    
}
