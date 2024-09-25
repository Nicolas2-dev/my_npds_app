<?php

namespace App\Modules\Users\Library;

use Npds\Config\Config;
use App\Modules\Users\Contracts\OnlineInterface;


class OnlineManager implements OnlineInterface 
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
     * [Who_Online description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online()
    {
        list($content1, $content2) = $this->Who_Online_Sub();
    
        return array($content1, $content2);
    }
    
    /**
     * [Who_Online_Sub description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online_Sub()
    {
        global $user, $cookie;
    
        list($member_online_num, $guest_online_num) = $this->site_load();

        $content1 = "$guest_online_num " . __d('users', 'visiteur(s) et') . " $member_online_num " . __d('users', 'membre(s) en ligne.');
    
        if ($user) {
            $content2 = __d('users', 'Vous êtes connecté en tant que') . " <b>" . $cookie[1] . "</b>";
        } else {
            $content2 = __d('users', 'Devenez membre privilégié en cliquant') . " <a href=\"user.php?op=only_newuser\">" . __d('users', 'ici') . "</a>";
        }
    
        return array($content1, $content2);
    }
    
    /**
     * [Site_Load description]
     *
     * @return  [type]  [return description]
     */
    public function Site_Load()
    {
        global $who_online_num;
    
        $guest_online_num = 0;
        $member_online_num = 0;
    
        $result = sql_query("SELECT COUNT(username) AS TheCount, guest 
                             FROM session 
                             GROUP BY guest");
    
        while ($TheResult = sql_fetch_assoc($result)) {
            if ($TheResult['guest'] == 0) {
                $member_online_num = $TheResult['TheCount'];
            } else {
                $guest_online_num = $TheResult['TheCount'];
            }
        }
    
        $who_online_num = $guest_online_num + $member_online_num;
    
        if (Config::get('SuperCache.SuperCache')) {
            $file = fopen("storage/cache/site_load.log", "w");
            fwrite($file, $who_online_num);
            fclose($file);
        }
    
        return array($member_online_num, $guest_online_num);
    }

    /**
     * [online_members description]
     *
     * @return  [type]  [return description]
     */
    public function online_members()
    {
        $result = sql_query("SELECT username, guest, time FROM session WHERE guest='0' ORDER BY username ASC");

        $i = 0; 

        $members_online[$i] = sql_num_rows($result);
    
        while ($session = sql_fetch_assoc($result)) {
            if (isset($session['guest']) and $session['guest'] == 0) {
                $i++;
                $members_online[$i]['username'] = $session['username'];
                $members_online[$i]['time'] = $session['time'];
            }
        }
    
        return $members_online;
    }

}
