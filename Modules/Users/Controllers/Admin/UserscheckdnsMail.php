<?php

namespace Modules\Users\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Mailer;


class Users extends AdminController
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
    protected $hlpfile = 'users';

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
    protected $f_meta_nom = 'mod_users';


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
        $this->f_titre = __d('users', 'Edition des Utilisateurs');

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
     * case 'checkdnsmail_users': =>checkdnsmailusers();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function checkdnsmailusers()
    {
        global $page, $end, $autocont;

        if (!isset($page)) {
            $page = 1;
        }
    
        if (!isset($end)) {
            $end = 0;
        }
    
        $pagesize   = 40;
        $min        = $pagesize * ($page - 1);
        $max        = $pagesize;
        $next_page  = $page + 1;
    
        $resource = sql_query("SELECT COUNT(uid) FROM users WHERE uid>1;");
        list($total) = sql_fetch_row($resource);
    
        if (($page * $pagesize) > $total) {
            $end = 1;
        }
    
        $result = sql_query("SELECT uid, uname, email FROM users WHERE uid>1 ORDER BY uid LIMIT $min,$max;");
        // $userchecked = sql_num_rows($result); ???
    
        $wrongdnsmail   = 0;
        $arrayusers     = array();
        $image          = '18.png';
    
        $subject    = __d('users', 'Votre adresse Email est incorrecte.');
        $time       = date(__d('users', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));
        $message    = __d('users', 'Votre adresse Email est incorrecte.') . ' (' . __d('users', 'DNS ou serveur de mail incorrect') . ').<br />' . __d('users', 'Tous vos abonnements vers cette adresse Email ont été suspendus.') . '<br /><a href="user.php?op=edituser">' . __d('users', 'Merci de fournir une nouvelle adresse Email valide.') . ' <i class="fa fa-user fa-2x align-middle fa-fw"></i></a><br />' . __d('users', 'Sans réponse de votre part sous 60 jours vous ne pourrez plus vous connecter en tant que membre sur ce site.') . ' ' . __d('users', 'Puis votre compte pourra être supprimé.') . '<br /><br />' . __d('users', 'Contacter l\'administration du site.') . '<a href="mailto:' . Config::get('npds.adminmail') . '" target="_blank"><i class="fa fa-at fa-2x align-middle fa-fw"></i>';
        
        $output = '';
        $contents = '';
    
        $filename = "storage/users_private/usersbadmail.txt";

        $handle = fopen($filename, "r");

        if (filesize($filename) > 0) {
            $contents = fread($handle, filesize($filename));
        }

        fclose($handle);
    
        $datenvoi = '';
        $datelimit = '';
    
        while (list($uid, $uname, $email) = sql_fetch_row($result)) {
            if (Mailer::checkdnsmail($email) === true and Mailer::isbadmailuser($uid) === true) {
                $re = '/#' . $uid . '\|(\d+)/m';

                $maj = preg_replace($re, '', $contents);
                $file = fopen("storage/users_private/usersbadmail.txt", 'w');
    
                fwrite($file, $maj);
                fclose($file);
            }
    
            if (Mailer::checkdnsmail($email) === false) {
                if (Mailer::isbadmailuser($uid) === false) {
    
                    $arrayusers[] = '#' . $uid . '|' . time();
    
                    //suspension des souscriptions
                    sql_query("DELETE FROM subscribe WHERE uid='$uid'");
    
                    global $aid;
                    Ecr_Log("security", "UnsubUser($uid) by AID : $aid", "");
    
                    //suspension de l'envoi des mails pour PM suspension lnl
                    sql_query("UPDATE users SET send_email='0', user_lnl='0' WHERE uid='$uid'");
    
                    //envoi private message
                    $sql = "INSERT INTO priv_msgs (msg_image, subject, from_userid, to_userid, msg_time, msg_text) VALUES ('$image', '$subject', '1', '$uid', '$time', '<br /><code>$email</code><br /><br />$message');";
                    sql_query($sql);
    
                    $datenvoi = date('d/m/Y');
                    $datelimit = date('d/m/Y', time() + 5184000);
                }
    
                if (Mailer::isbadmailuser($uid) === true) {
                    $re = '/#' . $uid . '\|(\d+)/m';
                    preg_match($re, $contents, $res);
    
                    $datenvoi = date('d/m/Y', $res[1]);
                    $datelimit = date('d/m/Y', $res[1] + 5184000);
                }
    
                $wrongdnsmail++;
                $output .= '<li>' . __d('users', 'DNS ou serveur de mail incorrect') . ' : <a class="alert-link" href="admin.php?chng_uid=' . $uid . '&amp;op=modifyUser">' . $uname . '</a><span class="float-end"><i class="far fa-envelope me-1 align-middle"></i><small>' . $datenvoi . '</small><i class="fa fa-ban mx-1 align-middle"></i><small>' . $datelimit . '</small></span></li>';
            }
        }
    
        $file = fopen("storage/users_private/usersbadmail.txt", 'a+');
        fwrite($file, implode('', $arrayusers));
        fclose($file);
    
        $ck = '';
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('users', 'Contrôle des serveurs de mails') . '</h3>
        <div class="alert alert-success lead">';
    
        if ($end != 1) {
            if (!isset($autocont)) {
                $autocont = 0;
            }
    
            if ($autocont == 1) {
                $ck = 'checked="checked"';
            } else {
                $ck = '';
            }
    
            echo '
            <div>' . __d('users', 'Serveurs de mails contrôlés') . '<span class="badge bg-success float-end">' . ($page * $pagesize) . '</span><br /></div>
            <a class="btn btn-success btn-sm mt-2" href="admin.php?op=checkdnsmail_users&amp;page=' . $next_page . '&amp;end=' . $end . '">Continuer</a>
            <hr />
            <div class="text-end"><input id="controlauto" ' . $ck . ' type="checkbox" /></div>
            <script type="text/javascript">
            //<![CDATA[
                $(function () {
                    check = $("#controlauto").is(":checked");
                    if(check)
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=1"; }, 3000);
                });
                $("#controlauto").on("click", function(){
                    check = $("#controlauto").is(":checked");
                    if(check)
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=1"; }, 3000);
                    else
                    setTimeout(function(){ document.location.href="admin.php?op=checkdnsmail_users&page=' . $next_page . '&end=' . $end . '&autocont=0"; }, 3000);
                });
            //]]>
            </script>';
        } else {
            echo __d('users', 'Serveurs de mails contrôlés') . '<span class="badge bg-success float-end">' . $total . '</span>';
        }

        echo'</div>';
    
        if ($end != 1) {
            if ($wrongdnsmail > 0) {
                echo '
                <div class="alert alert-danger">
                    <p class="lead">' . __d('users', 'DNS ou serveur de mail incorrect') . '<span class="badge bg-danger float-end">' . $wrongdnsmail . '</span></p>
                    <hr />
                    ' . __d('users', 'Toutes les souscriptions de ces utilisateurs ont été suspendues.') . '<br />
                    ' . __d('users', 'Un message privé leur a été envoyé sans réponse à ce message sous 60 jours ces utilisateurs ne pourront plus se connecter au site.') . '<br /><br />
                    <ul>' . $output . '</ul>
                </div>';
            } else
                echo '<div class="alert alert-success">OK</div>';
        }
    
        if ($end == 1) {
            $re = '/#(\d+)\|(\d+)/m';
            preg_match_all($re, $contents, $matches);
    
            $u      = $matches[1];
            $t      = $matches[2];
            $nbu    = count($u);
    
            $unames = array();
            $whereInParameters  = implode(',', $u); //
    
            $result = sql_query("SELECT uid, uname FROM users WHERE uid IN ($whereInParameters)");
    
            while ($names = sql_fetch_array($result)) {
                $unames[] = $names['uname'];
                $uids[] = $names['uid'];
            }
        
            echo '
            <div class="alert alert-danger">
                <div class="lead">' . __d('users', 'DNS ou serveur de mail incorrect') . ' <span class="badge bg-danger float-end">' . $nbu . '</span></div>';
    
            if ($nbu > 0) {
                echo '
                    <hr />' . __d('users', 'Toutes les souscriptions de ces utilisateurs ont été suspendues.') . '<br />
                ' . __d('users', 'Un message privé leur a été envoyé sans réponse à ce message sous 60 jours ces utilisateurs ne pourront plus se connecter au site.') . '<br /><br />
                <ul>';
    
                for ($row = 0; $row < $nbu; $row++) {
                    $dateenvoi = date('d/m/Y', $t[$row]);
                    $datelimit = date('d/m/Y', $t[$row] + 5184000);
    
                    echo '<li>' . __d('users', 'DNS ou serveur de mail incorrect') . ' <i class="fa fa-user-o me-1 "></i> : <a class="alert-link" href="admin.php?chng_uid=' . $uids[$row] . '&amp;op=modifyUser">' . $unames[$row] . '</a><span class="float-end"><i class="far fa-envelope me-1 align-middle"></i><small>' . $dateenvoi . '</small><i class="fa fa-ban mx-1 align-middle"></i><small>' . $datelimit . '</small></span></li>';
                }
    
                echo '</ul>';
            }
            echo '</div>';
        }
    
        Css::adminfoot('', '', '', '');
    }

}
