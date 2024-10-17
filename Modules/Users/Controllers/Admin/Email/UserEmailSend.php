<?php

namespace Modules\Users\Controllers\Admin\Email;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Mailer;


class UserEmail extends AdminController
{
/**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "email_user";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'email_user';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('users', 'Diffusion d\'un Message Interne');

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
     * Undocumented function
     *
     * @param [type] $username
     * @param [type] $subject
     * @param [type] $message
     * @param [type] $all
     * @param [type] $groupe
     * @param [type] $expediteur
     * @return void
     */
    public function send_email_to_user($username, $subject, $message, $all, $groupe, $expediteur)
    {
        if ($subject != '') {
            if ($expediteur == 1) {
                $emetteur = 1;
            } else {
                global $user;
    
                if ($user) {
                    $userX = base64_decode($user);
                    $userdata = explode(':', $userX);
                    $emetteur = $userdata[0];
                } else {
                    $emetteur = 1;
                }
            }
    
            if ($all) {
                $result = sql_query("SELECT uid, user_langue FROM users");
    
                while (list($to_userid, $user_langue) = sql_fetch_row($result)) {
                    $tab_to_userid[] = $to_userid . ':' . $user_langue;
                }
    
            } else {
                if ($groupe) {
                    $result = sql_query("SELECT s.uid, s.groupe, u.user_langue FROM users_status s, users u WHERE s.uid=u.uid AND s.groupe!='' ORDER BY s.uid ASC");
                    
                    while (list($to_userid, $groupeX, $user_langue) = sql_fetch_row($result)) {
                        $tab_groupe = explode(',', $groupeX);
    
                        if ($tab_groupe) {
                            foreach ($tab_groupe as $groupevalue) {
                                if ($groupevalue == $groupe) {
                                    $tab_to_userid[] = $to_userid . ':' . $user_langue;
                                }
                            }
                        }
                    }
    
                } else {
                    $result = sql_query("SELECT uid, user_langue FROM users WHERE uname='$username'");
    
                    while (list($to_userid, $user_langue) = sql_fetch_row($result)) {
                        $tab_to_userid[] = $to_userid . ':' . $user_langue;
                    }
                }
            }
    
            if (($subject == '') or ($message == '')) {
                header("location: admin.php");
            }
    
            $message = str_replace('\n', '<br />', $message);
    
            $time = date(__d('users', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));
    
            $pasfin = false;
            $count = 0;
    
            //  a revoir multilangue 
            //include_once("language/lang-multi.php");
    
            while ($count < sizeof($tab_to_userid)) {
                $to_tmp = explode(':', $tab_to_userid[$count]);
                $to_userid = $to_tmp[0];
    
                if (($to_userid != '') and ($to_userid != 1)) {
                    $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) ";
                    $sql .= "VALUES ('$image', '$subject', '$emetteur', '$to_userid', '$time', '$message')";
    
                    if ($resultX = sql_query($sql)) {
                        $pasfin = true;
                    }
    
                    // A copy in email if necessary
                    if (Config::get('npds.subscribe')) {
                        $old_message = $message;
    
                        // deprecated function a revboir multilangue
                        function translate_ml($msg) { return $msg; }

                        $sujet = translate_ml($to_tmp[1], 'Vous avez un nouveau message.');
                        
                        $message = translate_ml($to_tmp[1], 'Bonjour') . ",<br /><br /><a href=\"Config::get('npds.nuke_url')/viewpmsg.php\">" . translate_ml($to_tmp[1], "Cliquez ici pour lire votre nouveau message.") . "</a><br /><br />";
                        
                        include("signat.php");
    
                        Mailer::copy_to_email($to_userid, $sujet, $message);
    
                        $message = $old_message;
                    }
                }
    
                $count++;
            }
        }
    
        global $aid;
        Ecr_Log('security', "SendEmailToUser($subject) by AID : $aid", '');

        echo '<hr />';
    
        if ($pasfin) {
            echo '<div class="alert alert-success"><strong>"' . stripslashes($subject) . '"</strong> ' . __d('users', 'a été envoyée') . '.</div>';
        } else {
            echo '<div class="alert alert-danger"><strong>"' . stripslashes($subject) . '"</strong>' . __d('users', 'n\'a pas été envoyée') . '.</div>';
        }
        
        Css::adminfoot('', '', '', '');
    }

}
