<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class NewsFriend extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // settype($op, 'string');
        // settype($archive, 'string');
        
        // switch ($op) {
        //     case 'FriendSend':
        //         FriendSend($sid, $archive);
        //         break;
        
        //     case 'SendStory':
        //         SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse);
        //         break;
        
        //     case 'StorySent':
        //         StorySent($title, $fname);
        //         break;
        
        //     case 'SendSite':
        //         SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse);
        //         break;
        
        //     case 'SiteSent':
        //         SiteSent($fname);
        //         break;
                
        //     default:
        //         RecommendSite();
        //         break;
        // }
        
    }

    function FriendSend($sid, $archive)
    {
        
    
        settype($sid, "integer");
        settype($archive, "integer");
    
        $result = sql_query("SELECT title, aid FROM stories WHERE sid='$sid'");
        list($title, $aid) = sql_fetch_row($result);
    
        if (!$aid)
            header("Location: index.php");
    
        include("header.php");
    
        echo '
        <div class="card card-body">
        <h2><i class="fa fa-at fa-lg text-muted"></i>&nbsp;' . __d('news', 'Envoi de l\'article à un ami') . '</h2>
        <hr />
        <p class="lead">' . __d('news', 'Vous allez envoyer cet article') . ' : <strong>' . aff_langue($title) . '</strong></p>
        <form id="friendsendstory" action="friend.php" method="post">
            <input type="hidden" name="sid" value="' . $sid . '" />';
    
        global $user;
    
        $yn = '';
        $ye = '';
    
        if ($user) {
            global $cookie;
    
            $result = sql_query("SELECT name, email FROM users WHERE uname='$cookie[1]'");
            list($yn, $ye) = sql_fetch_row($result);
        }
    
        echo '
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fname" name="fname" maxlength="100" required="required" />
                <label for="fname">' . __d('news', 'Nom du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="fmail" name="fmail" maxlength="254" required="required" />
                <label for="fmail">' . __d('news', 'Email du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fmail"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="yname" name="yname" value="' . $yn . '" maxlength="100" required="required" />
                <label for="yname">' . __d('news', 'Votre nom') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_yname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="ymail" name="ymail" value="' . $ye . '" maxlength="254" required="required" />
                <label for="ymail">' . __d('news', 'Votre Email') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_ymail"></span></span>
            </div>';
    
        echo '' . Q_spambot();
    
        echo '
            <input type="hidden" name="archive" value="' . $archive . '" />
            <input type="hidden" name="op" value="SendStory" />
            <button type="submit" class="btn btn-primary" title="' . __d('news', 'Envoyer') . '"><i class="fa fa-lg fa-at"></i>&nbsp;' . __d('news', 'Envoyer') . '</button>
        </form>';
    
        $arg1 = '
        var formulid = ["friendsendstory"];
        inpandfieldlen("yname",100);
        inpandfieldlen("ymail",254);
        inpandfieldlen("fname",100);
        inpandfieldlen("fmail",254);';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse)
    {
        global $user;
    
        if (!$user) {
            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, '')) {
                Ecr_Log('security', "Send-Story Anti-Spam : name=" . $yname . " / mail=" . $ymail, '');
                redirect_url("index.php");
                die();
            }
        }

        settype($sid, 'integer');
        settype($archive, 'integer');
    
        $result2 = sql_query("SELECT title, time, topic FROM stories WHERE sid='$sid'");
        list($title, $time, $topic) = sql_fetch_row($result2);
    
        $result3 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
        list($topictext) = sql_fetch_row($result3);
    
        $subject = html_entity_decode(__d('news', 'Article intéressant sur'), ENT_COMPAT | ENT_HTML401, cur_charset) . Config::get('npds.sitename');
        $fname = removeHack($fname);
        $message = __d('news', 'Bonjour') . " $fname :\n\n" . __d('news', 'Votre ami') . " $yname " . __d('news', 'a trouvé cet article intéressant et a souhaité vous l\'envoyer.') . "\n\n" . aff_langue($title) . "\n" . __d('news', 'Date :') . " $time\n" . __d('news', 'Sujet : ') . " " . aff_langue($topictext) . "\n\n" . __d('news', 'L\'article') . " : <a href=\"Config::get('npds.nuke_url')/article.php?sid=$sid&amp;archive=$archive\">Config::get('npds.nuke_url')/article.php?sid=$sid&amp;archive=$archive</a>\n\n";
        
        include("signat.php");
    
        $fmail = removeHack($fmail);
        $subject = removeHack($subject);
        $message = removeHack($message);
        $yname = removeHack($yname);
        $ymail = removeHack($ymail);
    
        $stop = false;
    
        if ((!$fmail) || ($fmail == "") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $fmail))) 
            $stop = true;
    
        if ((!$ymail) || ($ymail == "") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $ymail))) 
            $stop = true;
    
        if (!$stop)
            send_email($fmail, $subject, $message, $ymail, false, 'html', '');
        else {
            $title = '';
            $fname = '';
        }
    
        $title = urlencode(aff_langue($title));
        $fname = urlencode($fname);
    
        Header("Location: friend.php?op=StorySent&title=$title&fname=$fname");
    }
    
    function StorySent($title, $fname)
    {
        include("header.php");
    
        $title = urldecode($title);
        $fname = urldecode($fname);
    
        if ($fname == '')
            echo '<div class="alert alert-danger">' . __d('news', 'Erreur : Email invalide') . '</div>';
        else
            echo '<div class="alert alert-success">' . __d('news', 'L\'article') . ' <strong>' . stripslashes($title) . '</strong> ' . __d('news', 'a été envoyé à') . '&nbsp;' . $fname . '<br />' . __d('news', 'Merci') . '</div>';
        
        include("footer.php");
    }
    
    function RecommendSite()
    {
        global $user;
    
        if ($user) {
            global $cookie;
    
            $result = sql_query("SELECT name, email FROM users WHERE uname='$cookie[1]'");
            list($yn, $ye) = sql_fetch_row($result);
        } else {
            $yn = '';
            $ye = '';
        }
    
        include("header.php");
    
        echo '
        <div class="card card-body">
        <h2>' . __d('news', 'Recommander ce site à un ami') . '</h2>
        <hr />
        <form id="friendrecomsite" action="friend.php" method="post">
            <input type="hidden" name="op" value="SendSite" />
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="yname" name="yname" value="' . $yn . '" required="required" maxlength="100" />
                <label for="yname">' . __d('news', 'Votre nom') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_yname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="ymail" name="ymail" value="' . $ye . '" required="required" maxlength="100" />
                <label for="ymail">' . __d('news', 'Votre Email') . '</label>
            </div>
            <span class="help-block text-end"><span class="muted" id="countcar_ymail"></span></span>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fname" name="fname" required="required" maxlength="100" />
                <label for="fname">' . __d('news', 'Nom du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="fmail" name="fmail" required="required" maxlength="100" />
                <label for="fmail">' . __d('news', 'Email du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fmail"></span></span>
            </div>
            ' . Q_spambot() . '
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-lg fa-at"></i>&nbsp;' . __d('news', 'Envoyer') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["friendrecomsite"];
        inpandfieldlen("yname",100);
        inpandfieldlen("ymail",100);
        inpandfieldlen("fname",100);
        inpandfieldlen("fmail",100);';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse)
    {
        global $user;
    
        if (!$user) {
            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, '')) {
                Ecr_Log('security', "Friend Anti-Spam : name=" . $yname . " / mail=" . $ymail, '');
                redirect_url("index.php");
                die();
            }
        }
    
        $subject = html_entity_decode(__d('news', 'Site à découvrir : '), ENT_COMPAT | ENT_HTML401, cur_charset) . Config::get('npds.sitename');
    
        $fname = removeHack($fname);
        $message = __d('news', 'Bonjour') . " $fname :\n\n" . __d('news', 'Votre ami') . " $yname " . __d('news', 'a trouvé notre site') . Config::get('npds.sitename') . __d('news', 'intéressant et a voulu vous le faire connaître.') . "\n\n".Config::get('npds.sitename')." : <a href=\"Config::get('npds.nuke_url')\">Config::get('npds.nuke_url')</a>\n\n";
        
        include("signat.php");
    
        $fmail = removeHack($fmail);
        $subject = removeHack($subject);
        $message = removeHack($message);
        $yname = removeHack($yname);
        $ymail = removeHack($ymail);
        $stop = false;
    
        if ((!$fmail) || ($fmail == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $fmail)))   
            $stop = true;
    
        if ((!$ymail) || ($ymail == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $ymail))) 
            $stop = true;
    
        if (!$stop)
            send_email($fmail, $subject, $message, $ymail, false, 'html', '');
        else
            $fname = '';
    
        Header("Location: friend.php?op=SiteSent&fname=$fname");
    }
    
    function SiteSent($fname)
    {
        include('header.php');
    
        if ($fname == '')
            echo '
                <div class="alert alert-danger lead" role="alert">
                    <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
                    ' . __d('news', 'Erreur : Email invalide') . '
                </div>';
        else
            echo '
            <div class="alert alert-success lead" role="alert">
                <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
                ' . __d('news', 'Nos références ont été envoyées à ') . ' ' . $fname . ', <br />
                <strong>' . __d('news', 'Merci de nous avoir recommandé') . '</strong>
            </div>';
    
        include('footer.php');
    }

}