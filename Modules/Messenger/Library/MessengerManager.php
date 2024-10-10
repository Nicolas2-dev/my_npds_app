<?php

namespace Modules\Messenger\Library;

use Npds\Config\Config;
use Modules\Theme\Support\Facades\Theme;
use Modules\Messenger\Contracts\MessengerInterface;

/**
 * Undocumented class
 */
class MessengerManager implements MessengerInterface 
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
     * [Mess_Check_Mail description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail($username)
    {
        $this->Mess_Check_Mail_interface($username, '');
    }
    
    /**
     * [Mess_Check_Mail_interface description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail_interface($username, $class)
    {
        if ($ibid = Theme::theme_image("fle_b.gif")) {
            $imgtmp = $ibid;
        } else {
            $imgtmp = false;
        }
    
        if ($class != "") {
            $class = "class=\"$class\"";
        }
    
        if ($username == Config::get('npds.anonymous')) {
            if ($imgtmp) {
                echo "<img alt=\"\" src=\"$imgtmp\" align=\"center\" />$username - <a href=\"user.php\" $class>" . __d('messenger', 'Votre compte') . "</a>";
            } else {
                echo "[$username - <a href=\"user.php\" $class>" . __d('messenger', 'Votre compte') . "</a>]";
            }
        } else {
            if ($imgtmp) {
                echo "<a href=\"user.php\" $class><img alt=\"\" src=\"$imgtmp\" align=\"center\" />" . __d('messenger', 'Votre compte') . "</a>&nbsp;" . $this->Mess_Check_Mail_Sub($username, $class);
            } else {
                echo "[<a href=\"user.php\" $class>" . __d('messenger', 'Votre compte') . "</a>&nbsp;&middot;&nbsp;" . $this->Mess_Check_Mail_Sub($username, $class) . "]";
            }
        }
    }
    
    /**
     * [Mess_Check_Mail_Sub description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail_Sub($username, $class)
    {
        // global $user;
    
        // if ($username) {
        //     $userdata = explode(':', base64_decode($user));
    
        //     $total_messages = sql_num_rows(sql_query("SELECT msg_id 
        //                                               FROM priv_msgs 
        //                                               WHERE to_userid = '$userdata[0]' 
        //                                               AND type_msg='0'"));
    
        //     $new_messages = sql_num_rows(sql_query("SELECT msg_id 
        //                                             FROM priv_msgs 
        //                                             WHERE to_userid = '$userdata[0]' 
        //                                             AND read_msg='0' 
        //                                             AND type_msg='0'"));
            
        //     if ($total_messages > 0) {
        //         if ($new_messages > 0) {
        //             $Xcheck_Nmail = $new_messages;
        //         } else {
        //             $Xcheck_Nmail = '0';
        //         }
    
        //         $Xcheck_mail = $total_messages;
        //     } else {
        //         $Xcheck_Nmail = '0';
        //         $Xcheck_mail = '0';
        //     }
        // }
    
        // $YNmail = "$Xcheck_Nmail";
        // $Ymail = "$Xcheck_mail";
        // $Mel = "<a href=\"viewpmsg.php\" $class>Mel</a>";
    
        // if ($Xcheck_Nmail > 0) {
        //     $YNmail = "<a href=\"viewpmsg.php\" $class>$Xcheck_Nmail</a>";
        //     $Mel = 'Mel';
        // }
    
        // if ($Xcheck_mail > 0) {
        //     $Ymail = "<a href=\"viewpmsg.php\" $class>$Xcheck_mail</a>";
        //     $Mel = 'Mel';
        // }
    
        // return ("$Mel : $YNmail / $Ymail");
    }    

    /**
     * [Form_instant_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    public function Form_instant_message($to_userid)
    {
        $this->write_short_private_message(removeHack($to_userid));
    }
    
    /**
     * [writeDB_private_message description]
     *
     * @param   [type]  $to_userid    [$to_userid description]
     * @param   [type]  $image        [$image description]
     * @param   [type]  $subject      [$subject description]
     * @param   [type]  $from_userid  [$from_userid description]
     * @param   [type]  $message      [$message description]
     * @param   [type]  $copie        [$copie description]
     *
     * @return  [type]                [return description]
     */
    public function writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie)
    {
        $res = sql_query("SELECT uid, user_langue FROM users WHERE uname='$to_userid'");
        list($to_useridx, $user_languex) = sql_fetch_row($res);
    
        if ($to_useridx == '')
            forumerror('0016');
        else {
            $time = date(__d('messenger', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));
    
            include_once("language/lang-multi.php");
    
            $subject = removeHack($subject);
            $message = str_replace("\n", "<br />", $message);
            $message = addslashes(removeHack($message));
    
            $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
            $sql .= "VALUES ('$image', '$subject', '$from_userid', '$to_useridx', '$time', '$message')";
    
            if (!$result = sql_query($sql))
                forumerror('0020');
    
            if ($copie) {
                $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text, type_msg, read_msg) ";
                $sql .= "VALUES ('$image', '$subject', '$from_userid', '$to_useridx', '$time', '$message', '1', '1')";
                
                if (!$result = sql_query($sql))
                    forumerror('0020');
            }
    
            if (Config::get('npds.subscribe')) {
                $sujet = html_entity_decode(translate_ml($user_languex, "Notification message privé."), ENT_COMPAT | ENT_HTML401, cur_charset) . '[' . $from_userid . '] / ' . Config::get('npds.sitename');
                $message = $time . '<br />' . translate_ml($user_languex, "Bonjour") . '<br />' . translate_ml($user_languex, "Vous avez un nouveau message.") . '<br /><br /><b>' . $subject . '</b><br /><br /><a href="' . Config::get('npds.nuke_url') . '/viewpmsg.php">' . translate_ml($user_languex, "Cliquez ici pour lire votre nouveau message.") . '</a><br />';
                
                include("signat.php");
    
                copy_to_email($to_useridx, $sujet, stripslashes($message));
            }
        }
    }
    
    /**
     * [write_short_private_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    public function write_short_private_message($to_userid)
    {
        echo '
        <h2>' . __d('messenger', 'Message à un membre') . '</h2>
        <h3><i class="fa fa-at me-1"></i>' . $to_userid . '</h3>
        <form id="sh_priv_mess" action="powerpack.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="subject" >' . __d('messenger', 'Sujet') . '</label>
                <div class="col-sm-12">
                    <input class="form-control" type="text" id="subject" name="subject" maxlength="100" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="message" >' . __d('messenger', 'Message') . '</label>
                <div class="col-sm-12">
                    <textarea class="form-control"  id="message" name="message" rows="10"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <div class="form-check" >
                    <input class="form-check-input" type="checkbox" id="copie" name="copie" />
                    <label class="form-check-label" for="copie">' . __d('messenger', 'Conserver une copie') . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <input type="hidden" name="to_userid" value="' . $to_userid . '" />
                <input type="hidden" name="op" value="write_instant_message" />
                <div class="col-sm-12">
                    <input class="btn btn-primary" type="submit" name="submit" value="' . __d('messenger', 'Valider') . '" accesskey="s" />&nbsp;
                    <button class="btn btn-secondary" type="reset">' . __d('messenger', 'Annuler') . '</button>
                </div>
            </div>
        </form>';
    }

}
