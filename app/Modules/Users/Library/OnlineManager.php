<?php

namespace App\Modules\Users\Library;

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Users\Contracts\OnlineInterface;

/**
 * Undocumented class
 */
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
        list($member_online_num, $guest_online_num) = $this->site_load();

        $content1 =  __d('users', '{0} visiteur(s) et {1} membre(s) en ligne.', $guest_online_num, $member_online_num);
    
        if (Auth::guard('user')) {

            $content2 = __d('users', 'Vous êtes connecté en tant que <b>{0}</b>', Cookie::explode(Auth::check('user'))[1]);
        } else {
            $content2 = __d('users', 'Devenez membre privilégié en cliquant') . ' <a href="'. site_url('user/newuser ') .'">' . __d('users', 'ici') . '</a>';
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
        $guest_online_num   = 0;
        $member_online_num  = 0;
    
        $session_temp = DB::table('session')->select(DB::raw('count(username) AS TheCount, guest'))->groupBy('guest')->asAssoc()->get();

        foreach ($session_temp as $TheResultk) {
            if ($TheResultk['guest'] == 0) {
                $member_online_num = $TheResultk['TheCount'];
            } else {
                $guest_online_num = $TheResultk['TheCount'];
            }
        }
    
        $who_online_num = $guest_online_num + $member_online_num;
    
        if (Config::get('SuperCache.SuperCache')) {
            $file = fopen(storage_path('cache/site_load.log'), 'w');
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
        $session_temp = DB::table('session')->select('username', 'guest', 'time')->where('guest', 0)->orderBy('username', 'asc');

        $i = 0; 

        $members_onlinef[$i] = $session_temp->count();

        foreach ($session_temp->get() as $sessionf) {
            if (isset($sessionf['guest']) and $sessionf['guest'] == 0) {
                $i++;
                $members_onlinef[$i]['username'] = $sessionf['username'];
                $members_onlinef[$i]['time'] = $sessionf['time'];
            }
        }

        return $members_onlinef;
    }

}
