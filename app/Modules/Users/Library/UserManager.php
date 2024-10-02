<?php

namespace App\Modules\Users\Library;

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Users\Contracts\UserInterface;

/**
 * Undocumented class
 */
class UserManager implements UserInterface 
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
     * [getuserinfo description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    public function getuserinfo($user)
    {
        $cookie = explode(':', base64_decode($user));

        $result = sql_query("SELECT pass 
                            FROM users 
                            WHERE uname='$cookie[1]'");

        list($pass) = sql_fetch_row($result);

        $userinfo = '';

        if (($cookie[2] == md5($pass)) and ($pass != '')) {
            $result = sql_query("SELECT uid, name, uname, email, femail, url, user_avatar, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax, user_journal, send_email, is_visible, mns, user_lnl 
                                FROM users 
                                WHERE uname='$cookie[1]'");
            
            if (sql_num_rows($result) == 1) {
                $userinfo = sql_fetch_assoc($result);
            } else {
                echo '<strong>' . __d('users', 'Un problème est survenu') . '.</strong>';
            }
        }

        return $userinfo;
    }

    /**
     * [AutoReg description]
     *
     * @return  [type]  [return description]
     */
    public function AutoReg()
    {
        $user = Auth::guard('user');

        if (!Config::get('npds.AutoRegUser')) {
            if (isset($user)) {
                $cookie = explode(':', base64_decode(Auth::check('user')));

                $status_temp = DB::table('users_status')->select('open')->where('uid', $cookie[0])->first();

                if (!$status_temp['open']) {
                    Cookie::set('user', '', 0);

                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    /**
     * [get_moderator description]
     *
     * @param   [type]  $user_id  [$user_id description]
     *
     * @return  [type]            [return description]
     */
    public function get_moderator($user_id)
    {
        $user_id = str_replace(",", "' or uid='", $user_id);
    
        if ($user_id == 0)
            return ("None");
    
        $rowQ1 = Q_Select("SELECT uname FROM users WHERE id='$user_id'", 3600);
        $modslist = '';
    
        foreach ($rowQ1 as $modnames) {
            foreach ($modnames as $modname) {
                $modslist .= $modname . ' ';
            }
        }
    
        return (chop($modslist));
    }
    
    /**
     * [user_is_moderator description]
     *
     * @param   [type]  $uidX           [$uidX description]
     * @param   [type]  $passwordX      [$passwordX description]
     * @param   [type]  $forum_accessX  [$forum_accessX description]
     *
     * @return  [type]                  [return description]
     */
    public function user_is_moderator($uidX, $passwordX, $forum_accessX)
    {
        $result1 = sql_query("SELECT pass FROM users WHERE uid = '$uidX'");
        $result2 = sql_query("SELECT level FROM users_status WHERE uid = '$uidX'");
    
        $userX = sql_fetch_assoc($result1);
    
        $password = $userX['pass'];
    
        $userX = sql_fetch_assoc($result2);
    
        if ((md5($password) == $passwordX) and ($forum_accessX <= $userX['level']) and ($userX['level'] > 1))
            return ($userX['level']);
        else
            return (false);
    }
    
    /**
     * [get_userdata_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_from_id($userid)
    {
        $sql1 = "SELECT * FROM users WHERE uid='$userid'";
        $sql2 = "SELECT * FROM users_status WHERE uid='$userid'";
    
        if (!$result = sql_query($sql1))
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array("uid" => 1);
        else
            $myrow = array_merge($myrow, (array)sql_fetch_assoc(sql_query($sql2)));
    
        return ($myrow);
    }
    
    /**
     * [get_userdata_extend_from_id description]
     *
     * @param   [type]  $userid  [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function get_userdata_extend_from_id($userid)
    {
        $sql1 = "SELECT * FROM users_extend WHERE uid='$userid'";
        /*   $sql2 = "SELECT * FROM users_status WHERE uid='$userid'";
    
        if (!$result = sql_query($sql1))  
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array( "id" => 1);
        else
            $myrow=array_merge($myrow,(array)sql_fetch_assoc(sql_query($sql1)));
        */
    
        $myrow = (array)sql_fetch_assoc(sql_query($sql1));
    
        return ($myrow);
    }
    
    /**
     * [get_userdata description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    public function get_userdata($username)
    {
        $sql = "SELECT * FROM users WHERE uname='$username'";
    
        if (!$result = sql_query($sql))
            forumerror('0016');
    
        if (!$myrow = sql_fetch_assoc($result))
            $myrow = array("uid" => 1);
    
        return ($myrow);
    }

    function userCheck($uname, $email)
    {
        $stop = '';
    
        if ((!$email) || ($email == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $email))) {
            $stop = __d('users', 'Erreur : Email invalide');
        }
    
        if (strrpos($email, ' ') > 0) {
            $stop = __d('users', 'Erreur : une adresse Email ne peut pas contenir d\'espaces');
        }
    
        if (Mailer::checkdnsmail($email) === false) {
            $stop = __d('users', 'Erreur : DNS ou serveur de mail incorrect !');
        }
    
        if ((!$uname) || ($uname == '') || (preg_match('#[^a-zA-Z0-9_-]#', $uname))) {
            $stop = __d('users', 'Erreur : identifiant invalide');
        }
    
        if (strlen($uname) > 25) {
            $stop = __d('users', 'Votre surnom est trop long. Il doit faire moins de 25 caractères.');
        }
    
        if (preg_match('#^(root|adm|linux|webmaster|admin|god|administrator|administrador|nobody|anonymous|anonimo|an€nimo|operator|dune|netadm)$#i', $uname)) {
            $stop = __d('users', 'Erreur : nom existant.');
        }
    
        if (strrpos($uname, ' ') > 0) {
            $stop = __d('users', 'Il ne peut pas y avoir d\'espace dans le surnom.');
        }
    
        if (sql_num_rows(sql_query("SELECT uname FROM users WHERE uname='$uname'")) > 0) {
            $stop = __d('users', 'Erreur : cet identifiant est déjà utilisé');
        }
    
        if ($uname != 'edituser')
            if (sql_num_rows(sql_query("SELECT email FROM users WHERE email='$email'")) > 0) {
                $stop = __d('users', 'Erreur : adresse Email déjà utilisée');
            }
    
        return $stop;
    }

    /**
     * [user_rank description]
     *
     * @param   [type]  $user_id [$userid description]
     *
     * @return  [type]           [return description]
     */
    public function user_rang($user_id)
    {
        $user_rang = DB::table('users_status')->select('rang')->where('uid', $user_id)->first();

        if ($user_rang['rang']) {

            if ($rank = DBQ_Select(DB::table('config')->select('rank1', 'rank2', 'rank3', 'rank4', 'rank5')->first(), 86400)) {
                
                if (!empty($rank['rank1'])) {
                    $messR = 'rank' . $rank['rank'. $user_rang['rang']];
                } else {
                    $messR = '';
                }
            }
 
            $rang_img = '<img src="' . Theme::theme_image_row('forum/rank/' . $user_rang['rang'] . '.png', 'forum') . '" border="0" alt="' . Language::aff_langue($messR) . '" title="' . Language::aff_langue($messR) . '" loading="lazy" />';
        } else {
            $rang_img = '&nbsp;';
        } 
                
        return $rang_img;
    }



}
