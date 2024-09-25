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
class UserEdit extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'edituser':
        //         if ($user)
        //             edituser();
        //         else
        //             Header("Location: index.php");
        //         break;
        
        //     case 'saveuser':
        //         $past = time() - 300;
        
        //         sql_query("DELETE FROM session WHERE time < $past");
        
        //         $result = sql_query("SELECT time FROM session WHERE username='$cookie[1]'");
        
        //         if (sql_num_rows($result) == 1) {
        //             // CheckBox
        //             settype($attach, 'integer');
        //             settype($user_viewemail, 'integer');
        //             settype($usend_email, 'integer');
        //             settype($uis_visible, 'integer');
        //             settype($user_lnl, 'integer');
        //             settype($raz_avatar, 'integer');
        //             saveuser($uid, $name, $uname, $email, $femail, $url, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $attach, $usend_email, $uis_visible, $user_lnl, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1, $MAX_FILE_SIZE, $raz_avatar);
        //         } else
        //             Header("Location: user.php");
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
     * [edituser description]
     *
     * @return  [type]  [return description]
     */
    public function edituser()
    {
        global $user;
    
        $userinfo = getusrinfo($user);
    
        member_menu($userinfo['mns'], $userinfo['uname']);
    
        global $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1;
        $result = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2, B1 FROM users_extend WHERE uid='" . $userinfo['uid'] . "'");
        list($C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1) = sql_fetch_row($result);
        
        showimage();
    
        include("library/sform/extend-user/mod_extend-user.php");
    }
    
    /**
     * [saveuser description]
     *
     * @param   [type]  $uid             [$uid description]
     * @param   [type]  $name            [$name description]
     * @param   [type]  $uname           [$uname description]
     * @param   [type]  $email           [$email description]
     * @param   [type]  $femail          [$femail description]
     * @param   [type]  $url             [$url description]
     * @param   [type]  $pass            [$pass description]
     * @param   [type]  $vpass           [$vpass description]
     * @param   [type]  $bio             [$bio description]
     * @param   [type]  $user_avatar     [$user_avatar description]
     * @param   [type]  $user_occ        [$user_occ description]
     * @param   [type]  $user_from       [$user_from description]
     * @param   [type]  $user_intrest    [$user_intrest description]
     * @param   [type]  $user_sig        [$user_sig description]
     * @param   [type]  $user_viewemail  [$user_viewemail description]
     * @param   [type]  $attach          [$attach description]
     * @param   [type]  $usend_email     [$usend_email description]
     * @param   [type]  $uis_visible     [$uis_visible description]
     * @param   [type]  $user_lnl        [$user_lnl description]
     * @param   [type]  $C1              [$C1 description]
     * @param   [type]  $C2              [$C2 description]
     * @param   [type]  $C3              [$C3 description]
     * @param   [type]  $C4              [$C4 description]
     * @param   [type]  $C5              [$C5 description]
     * @param   [type]  $C6              [$C6 description]
     * @param   [type]  $C7              [$C7 description]
     * @param   [type]  $C8              [$C8 description]
     * @param   [type]  $M1              [$M1 description]
     * @param   [type]  $M2              [$M2 description]
     * @param   [type]  $T1              [$T1 description]
     * @param   [type]  $T2              [$T2 description]
     * @param   [type]  $B1              [$B1 description]
     * @param   [type]  $MAX_FILE_SIZE   [$MAX_FILE_SIZE description]
     * @param   [type]  $raz_avatar      [$raz_avatar description]
     *
     * @return  [type]                   [return description]
     */
    public function saveuser($uid, $name, $uname, $email, $femail, $url, $pass, $vpass, $bio, $user_avatar, $user_occ, $user_from, $user_intrest, $user_sig, $user_viewemail, $attach, $usend_email, $uis_visible, $user_lnl, $C1, $C2, $C3, $C4, $C5, $C6, $C7, $C8, $M1, $M2, $T1, $T2, $B1, $MAX_FILE_SIZE, $raz_avatar)
    {
        global $user, $userinfo;
    
        $cookie = cookiedecode($user);
        $check = $cookie[1];
    
        $result = sql_query("SELECT uid, email FROM users WHERE uname='$check'");
        list($vuid, $vemail) = sql_fetch_row($result);
    
        if (($check == $uname) and ($uid == $vuid)) {
            if ((isset($pass)) && ("$pass" != "$vpass")) {
                message_error('<i class="fa fa-exclamation me-2"></i>' . __d('users', 'Les mots de passe sont différents. Ils doivent être identiques.') . '<br />', '');
            
            } elseif (($pass != '') && (strlen($pass) < Config::get('npds.minpass'))) {
                message_error('<i class="fa fa-exclamation me-2"></i>' . __d('users', 'Désolé, votre mot de passe doit faire au moins') . ' <strong>' . Config::get('npds.minpass') . '</strong> ' . __d('users', 'caractères') . '<br />', '');
            
            } else {
                $stop = userCheck('edituser', $email);
    
                if (!$stop) {
                    $contents = '';
                    $filename = "storage/users_private/usersbadmail.txt";
                    $handle = fopen($filename, "r");
    
                    if (filesize($filename) > 0) {
                        $contents = fread($handle, filesize($filename));
                    }
    
                    fclose($handle);
    
                    $re = '/#' . $uid . '\|(\d+)/m';
                    $maj = preg_replace($re, '', $contents);
                    $file = fopen("storage/users_private/usersbadmail.txt", 'w');
    
                    fwrite($file, $maj);
                    fclose($file);
    
                    if ($bio) {
                        $bio = FixQuotes(strip_tags($bio));
                    }
    
                    $t = $attach ? 1 : 0;
                    $a = $user_viewemail ? 1 : 0;
                    $u = $usend_email ? 1 : 0;
                    $v = $uis_visible ? 0 : 1;
                    $w = $user_lnl ? 1 : 0;
    
                    include_once("modules/upload/upload.conf.php");

                    $avatar_limit = explode("*", Config::get('npds.avatar_size', '80*100'));
                    $rep = $DOCUMENTROOT != '' ? $DOCUMENTROOT : $_SERVER['DOCUMENT_ROOT'];
    
                    if ($B1 != 'none') {
    
                        include_once("modules/upload/lang/upload.lang-Config::get('npds.language').php");
                        include_once("modules/upload/clsUpload.php");
    
                        $upload = new Upload();
    
                        $upload->maxupload_size = $MAX_FILE_SIZE;
                        $field1_filename = trim($upload->getFileName("B1"));
                        $suffix = strtoLower(substr(strrchr($field1_filename, '.'), 1));
    
                        if (($suffix == 'gif') or ($suffix == 'jpg') or ($suffix == 'png') or ($suffix == 'jpeg')) {
                            $field1_filename = removeHack(preg_replace('#[/\\\:\*\?"<>|]#i', '', rawurldecode($field1_filename)));
                            $field1_filename = preg_replace('#\.{2}|config.php|/etc#i', '', $field1_filename);
    
                            if ($field1_filename) {
                                if ($autorise_upload_p) {
                                    $user_dir = $racine . '/storage/users_private/' . $uname . '/';
    
                                    if (!is_dir($rep . $user_dir)) {
                                        @umask("0000");
    
                                        if (@mkdir($rep . $user_dir, 0777)) {
                                            $fp = fopen($rep . $user_dir . 'index.html', 'w');
                                            fclose($fp);
                                        } else {
                                            $user_dir = $racine . '/storage/users_private/';
                                        }
                                    }
                                } else {
                                    $user_dir = $racine . '/storage/users_private/';
                                }
    
                                if ($upload->saveAs($uname . '.' . $suffix, $rep . $user_dir, 'B1', true)) {
                                    $old_user_avatar = $user_avatar;
                                    $user_avatar = $user_dir . $uname . '.' . $suffix;
                                    $img_size = @getimagesize($rep . $user_avatar);
    
                                    if (($img_size[0] > $avatar_limit[0]) or ($img_size[1] > $avatar_limit[1])) {
                                        $raz_avatar = true;
                                    }
    
                                    if ($racine == '') {
                                        $user_avatar = substr($user_avatar, 1);
                                    }
                                }
                            }
                        }
                    }
    
                    if ($raz_avatar) {
                        if (strstr($user_avatar, '/users_private')) {
                            @unlink($rep . $user_avatar);
                            @unlink($rep . $old_user_avatar);
                        }
    
                        $user_avatar = 'blank.gif';
                    }
    
                    if ($pass != '') {
                        cookiedecode($user);
                        $AlgoCrypt = PASSWORD_BCRYPT;
                        $min_ms = 100;
                        $options = ['cost' => getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms),];
                        $hashpass = password_hash($pass, PASSWORD_BCRYPT, $options);
                        $pass = crypt($pass, $hashpass);
    
                        sql_query("UPDATE users SET name='$name', email='$email', femail='" . removeHack($femail) . "', url='" . removeHack($url) . "', pass='$pass', hashkey='1', bio='" . removeHack($bio) . "', user_avatar='$user_avatar', user_occ='" . removeHack($user_occ) . "', user_from='" . removeHack($user_from) . "', user_intrest='" . removeHack($user_intrest) . "', user_sig='" . removeHack($user_sig) . "', user_viewemail='$a', send_email='$u', is_visible='$v', user_lnl='$w' WHERE uid='$uid'");
                        $result = sql_query("SELECT uid, uname, pass, storynum, umode, uorder, thold, noscore, ublockon, theme FROM users WHERE uname='$uname' AND pass='$pass'");
                        
                        if (sql_num_rows($result) == 1) {
                            $userinfo = sql_fetch_assoc($result);
                            docookie($userinfo['uid'], $userinfo['uname'], $userinfo['pass'], $userinfo['storynum'], $userinfo['umode'], $userinfo['uorder'], $userinfo['thold'], $userinfo['noscore'], $userinfo['ublockon'], $userinfo['theme'], $userinfo['commentmax'], "", $skin);
                        }
                    } else {
                        sql_query("UPDATE users SET name='$name', email='$email', femail='" . removeHack($femail) . "', url='" . removeHack($url) . "', bio='" . removeHack($bio) . "', user_avatar='$user_avatar', user_occ='" . removeHack($user_occ) . "', user_from='" . removeHack($user_from) . "', user_intrest='" . removeHack($user_intrest) . "', user_sig='" . removeHack($user_sig) . "', user_viewemail='$a', send_email='$u', is_visible='$v', user_lnl='$w' WHERE uid='$uid'");
                    }

                    sql_query("UPDATE users_status SET attachsig='$t' WHERE uid='$uid'");
                    $result = sql_query("SELECT uid FROM users_extend WHERE uid='$uid'");
    
                    if (sql_num_rows($result) == 1) {
                        sql_query("UPDATE users_extend SET C1='" . removeHack($C1) . "', C2='" . removeHack($C2) . "', C3='" . removeHack($C3) . "', C4='" . removeHack($C4) . "', C5='" . removeHack($C5) . "', C6='" . removeHack($C6) . "', C7='" . removeHack($C7) . "', C8='" . removeHack($C8) . "', M1='" . removeHack($M1) . "', M2='" . removeHack($M2) . "', T1='" . removeHack($T1) . "', T2='" . removeHack($T2) . "', B1='$B1' WHERE uid='$uid'");
                    } else {
                        $result = sql_query("INSERT INTO users_extend VALUES ('$uid','" . removeHack($C1) . "', '" . removeHack($C2) . "', '" . removeHack($C3) . "', '" . removeHack($C4) . "', '" . removeHack($C5) . "', '" . removeHack($C6) . "', '" . removeHack($C7) . "', '" . removeHack($C8) . "', '" . removeHack($M1) . "', '" . removeHack($M2) . "', '" . removeHack($T1) . "', '" . removeHack($T2) . "', '$B1')");
                    }

                    if ($pass != '') {
                        logout();
                    } else {
                        header("location: user.php?op=edituser");
                    }
                } else {
                    message_error($stop, '');
                }
            }
        } else {
            Header("Location: index.php");
        }
    }

}