<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class NewsSubmit extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // settype($op, 'string');
        
        // switch ($op) {
        //     case 'Prévisualiser':
        //     case __d('news', 'Prévisualiser'):
        //         if ($user) {
        //             $userinfo = getuserinfo($user);
        //             $name = $userinfo['uname'];
        //         } else
        //             $name = Config::get('npds.anonymous');
        
        //         PreviewStory($name, $subject, $story, $bodytext, $topic, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
        
        //         break;
        //     case 'Ok':
        //         settype($date_debval, 'string');
        
        //         if (!$date_debval)
        //             $date_debval = $dd_pub . ' ' . $dh_pub . ':01';
        
        //         settype($date_finval, 'string');
        
        //         if (!$date_finval)
        //             $date_finval = $fd_pub . ' ' . $fh_pub . ':01';
        
        //         if ($date_finval < $date_debval)
        //             $date_finval = $date_debval;
                
        //         SubmitStory($subject, $story, $bodytext, $topic, $date_debval, $date_finval, $epur, $asb_question, $asb_reponse);
        //         break;
        //     default:
        //         defaultDisplay();
        //         break;
    }

    public function index()
    {
        include("publication.php");

        settype($user, 'string');
        
        if (Config::get('npds.mod_admin_news') > 0) {
            if ($admin == '' and $user == '') {
                Header("Location: index.php");
                exit;
            }
        
            if (Config::get('npds.mod_admin_news') == 1) {
                if ($user != '' and $admin == '') {
                    global $cookie;
        
                    $result = sql_query("SELECT level FROM users_status WHERE uid='$cookie[0]'");
        
                    if (sql_num_rows($result) == 1) {
                        list($userlevel) = sql_fetch_row($result);
        
                        if ($userlevel == 1) {
                            Header("Location: index.php");
                            exit;
                        }
                    }
                }
            }
        }
        
        function defaultDisplay()
        {
            
        
            include('header.php');
        
            global $user;
            if ($user) 
                $userinfo = getuserinfo($user);
        
            echo '
            <h2>' . __d('news', 'Proposer un article') . '</h2>
            <hr />
            <form action="submit.php" method="post" name="adminForm">';
        
            echo '<p class="lead"><strong>' . __d('news', 'Votre nom') . '</strong> : ';
        
            if ($user) {
                echo '<a href="user.php">' . $userinfo['uname'] . '</a> [ <a href="user.php?op=logout">' . __d('news', 'Déconnexion') . '</a> ]</p>
                <input type="hidden" name="name" value="' . $userinfo['name'] . '" />';
            } else {
                echo Config::get('npds.anonymous') . '[ <a href="user.php">' . __d('news', 'Nouveau membre') . '</a> ]</p>
                <input type="hidden" name="name" value="' . Config::get('npds.anonymous') . '" />';
            }
        
            echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3" for="subject">' . __d('news', 'Titre') . '</label>
                    <div class="col-sm-9">
                        <input type="text" id="subject" name="subject" class="form-control" autofocus="autofocus">
                        <p class="help-block">' . __d('news', 'Faites simple') . '! ' . __d('news', 'Mais ne titrez pas -un article-, ou -à lire-,...') . '</p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3" for="topic">' . __d('news', 'Sujet') . '</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="topic">';
        
            $toplist = sql_query("SELECT topicid, topicname, topictext FROM topics ORDER BY topictext");
        
            echo '<option value="">' . __d('news', 'Sélectionner un sujet') . '</option>';
        
            settype($topic, 'string');
            settype($sel, 'string');
        
            while (list($topicid, $topiname, $topics) = sql_fetch_row($toplist)) {
                if ($topicid == $topic) 
                    $sel = 'selected="selected" ';
        
                echo '<option ' . $sel . ' value="' . $topicid . '">';
        
                if ($topics != '') 
                    echo aff_langue($topics);
                else 
                    echo $topiname;
        
                echo '</option>';
        
                $sel = '';
            }
        
            echo '
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="story" >' . __d('news', 'Texte d\'introduction') . '</label>
                    <div class="col-sm-12">
                        <textarea class=" form-control tin" rows="25" id="story" name="story"></textarea>
                    </div>
                </div>';
        
            echo aff_editeur('story', '');
        
            echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="bodytext">' . __d('news', 'Texte complet') . '</label>
                    <div class="col-sm-12">
                        <textarea class="form-control tin " rows="25" id="bodytext" name="bodytext"></textarea>
                    </div>
                </div>';
        
            echo aff_editeur('bodytext', '');
            publication('', '', '', '', 0);
        
            echo '
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <span class="help-block">' . __d('news', 'Vous devez prévisualiser avant de pouvoir envoyer') . '</span>
                        <input class="btn btn-outline-primary" type="submit" name="op" value="' . __d('news', 'Prévisualiser') . '" />
                    </div>
                </div>
            </form>';
        
            include('footer.php');
        }
        
        function PreviewStory($name, $subject, $story, $bodytext, $topic, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
        {
            global $topictext, $topicimage;
        
            $topiclogo = '<span class="badge bg-secondary float-end"><strong>' . aff_langue($topictext) . '</strong></span>';
        
            include('header.php');
        
            $story = stripslashes(dataimagetofileurl($story, 'cache/ai'));
            $bodytext = stripslashes(dataimagetofileurl($bodytext, 'cache/ac'));
            $subject = stripslashes(str_replace('"', '&quot;', (strip_tags($subject))));
        
            echo '
            <h2>' . __d('news', 'Proposer un article') . '</h2>
            <hr />
            <form action="submit.php" method="post" name="adminForm">
                <p class="lead"><strong>' . __d('news', 'Votre nom') . '</strong> : ' . $name . '</p>
                <input type="hidden" name="name" value="' . $name . '" />
                <div class="card card-body mb-4">';
        
            if ($topic == '') {
                $topicimage = 'all-topics.gif';
                $warning = '<div class="alert alert-danger"><strong>' . __d('news', 'Sélectionner un sujet') . '</strong></div>';
            } else {
                $warning = '';
                $result = sql_query("SELECT topictext, topicimage FROM topics WHERE topicid='$topic'");
                list($topictext, $topicimage) = sql_fetch_row($result);
            }
        
            if ($topicimage !== '') {
                if (!$imgtmp = theme_image('topics/' . $topicimage)) {
                    $imgtmp = Config::get('npds.tipath') . $topicimage;
                }
        
                $timage = $imgtmp;
        
                if (file_exists($imgtmp))
                    $topiclogo = '<img class="img-fluid n-sujetsize" src="' . $timage . '" align="right" alt="" />';
            }
        
            $storyX = aff_code($story);
            $bodytextX = aff_code($bodytext);
        
            themepreview('<h3>' . $subject . $topiclogo . '</h3>', '<div class="text-muted">' . $storyX . '</div>', $bodytextX);
        
            //    if ($no_img) {
            //       echo '<strong>'.aff_langue($topictext).'</strong>';
            //    }
        
            echo '
            </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3" for="subject">' . __d('news', 'Titre') . '</label>
                    <div class="col-sm-9">
                        <input type="text" name="subject" class="form-control" value="' . $subject . '" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3" for="topic">' . __d('news', 'Sujet') . '</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="topic">';
        
            $toplist = sql_query("SELECT topicid, topictext FROM topics ORDER BY topictext");
        
            echo '<option value="">' . __d('news', 'Sélectionner un sujet') . '</option>';
        
            while (list($topicid, $topics) = sql_fetch_row($toplist)) {
                if ($topicid == $topic) {
                    $sel = 'selected="selected" ';
                }
        
                echo '<option ' . $sel . ' value="' . $topicid . '">' . aff_langue($topics) . '</option>';
        
                $sel = '';
            }
        
            echo '
                        </select>
                        <span class="help-block text-danger">' . $warning . '</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="story">' . __d('news', 'Texte d\'introduction') . '</label>
                    <div class="col-sm-12">
                        <span class="help-block">' . __d('news', 'Les spécialistes peuvent utiliser du HTML, mais attention aux erreurs') . '</span>
                        <textarea class="tin form-control" rows="25" name="story">' . $story . '</textarea>';
        
            echo aff_editeur('story', '');
        
            echo '</div>
                </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-12">' . __d('news', 'Texte complet') . '</label>
                        <div class="col-sm-12">
                        <textarea class="tin form-control" rows="25" name="bodytext">' . $bodytext . '</textarea>
                        </div>
                    </div>';
        
            echo aff_editeur('bodytext', '');
        
            publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
            echo Q_spambot();
        
            echo '
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                        <input class="btn btn-secondary" type="submit" name="op" value="' . __d('news', 'Prévisualiser') . '" />&nbsp;
                        <input class="btn btn-primary" type="submit" name="op" value="Ok" />
                        </div>
                    </div>
            </form>';
        
            include('footer.php');
        }
        
        function submitStory($subject, $story, $bodytext, $topic, $date_debval, $date_finval, $epur, $asb_question, $asb_reponse)
        {
            global $user, $EditedMessage;
        
            if ($user != '') {
                global $cookie;
        
                $uid = $cookie[0];
                $name = $cookie[1];
            } else {
                $uid = -1;
                $name = Config::get('npds.anonymous');
        
                //anti_spambot
                if (!R_spambot($asb_question, $asb_reponse, '')) {
                    Ecr_Log('security', "Submit Anti-Spam : uid=" . $uid . " / name=" . $name, '');
                    redirect_url("index.php");
                    die();
                }
            }
        
            $story = dataimagetofileurl($story, 'cache/ai');
            $bodytext = dataimagetofileurl($bodytext, 'cache/ac');
            $subject = removeHack(stripslashes(FixQuotes(str_replace("\"", "&quot;", (strip_tags($subject))))));
            $story = removeHack(stripslashes(FixQuotes($story)));
            $bodytext = removeHack(stripslashes(FixQuotes($bodytext)));
        
            $result = sql_query("INSERT INTO queue VALUES (NULL, '$uid', '$name', '$subject', '$story', '$bodytext', now(), '$topic','$date_debval','$date_finval','$epur')");
            
            if (sql_last_id()) {
                if (Config::get('npds.notify')) {
        
                    send_email(Config::get('npds.notify_email'), Config::get('npds.notify_subject'), Config::get('npds.notify_message'), Config::get('npds.notify_from'), false, "html", '');
                }
        
                include('header.php');
        
                echo '
                <h2>' . __d('news', 'Proposer un article') . '</h2>
                <hr />
                <div class="alert alert-success lead">' . __d('news', 'Merci pour votre contribution.') . '</div>';
        
                include('footer.php');
            } else {
                include('header.php');
                echo sql_error();
                include('footer.php');
            }
        }
        
    }

}