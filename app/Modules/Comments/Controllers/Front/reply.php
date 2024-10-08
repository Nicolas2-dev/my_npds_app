<?php


// if (!function_exists("Mysql_Connexion"))
//     die();


// include('auth.php');

// filtre_module($file_name);

// if (file_exists("modules/comments/$file_name.conf.php"))
//     include("modules/comments/$file_name.conf.php");
// else
//     die();

if (isset($cancel))
    header("Location: $url_ret");

// settype($forum, 'integer');

if ($forum >= 0)
    die();

// gestion des params du 'forum' : type, accès, modérateur ...
$forum_name = 'comments';
$forum_type = 0;
$allow_to_post = false;
$forum_access = Config::get('npds.anonpost') ? 0 : 1;

global $user;
if (Config::get('npds.moderate') == 1 and isset($admin))
    $Mmod = true;
elseif (Config::get('npds.moderate') == 2) {
    $userX = base64_decode($user);
    $userdata = explode(':', $userX);

    $result = sql_query("SELECT level FROM users_status WHERE uid='" . $userdata[0] . "'");
    list($level) = sql_fetch_row($result);

    $Mmod = $level >= 2 ? true : false;
} else
    $Mmod = false;
// gestion des params du 'forum' : type, accès, modérateur ...

if (isset($submitS)) {
    $stop = 0;
    if ($message == '') 
        $stop = 1;

    if (!$user) {
        if ($forum_access == 0) {
            $userdata = array('uid' => 1);

            // include('header.php');
        } else {
            if (($username == '') or ($password == ''))
                forumerror('0027');
            else {
                $result = sql_query("SELECT pass FROM users WHERE uname='$username'");
                list($pass) = sql_fetch_row($result);

                $passwd = (!$system) ? crypt($password, $pass) : $password;

                if ((strcmp($passwd, $pass) == 0) and ($pass != '')) {
                    $userdata = get_userdata($username);

                    // include('header.php');
                } else
                    forumerror('0028');
            }
        }
    } else {
        $userX = base64_decode($user);
        $userdata = explode(':', $userX);
        $userdata = get_userdata($userdata[1]);

        // include("header.php");
    }

    // Either valid user/pass, or valid session. continue with post.
    if ($stop != 1) {
        $poster_ip =  getip();
        $hostname = Config::get('npds.dns_verif') ? @gethostbyaddr($poster_ip) : $poster_ip;

        // anti flood
        anti_flood($Mmod, $anti_flood, $poster_ip, $userdata, Config::get('npds.gmt'));

        //anti_spambot
        if (isset($asb_question) and isset($asb_reponse)) {
            if (!R_spambot($asb_question, $asb_reponse, $message)) {
                Ecr_Log('security', "Forum Anti-Spam : forum=" . $forum . " / topic=" . $topic, '');
                redirect_url("$url_ret");
                die();
            }
        }

        if ($formulaire != '')
            include("modules/comments/comments_extender.php");

        if ($allow_html == 0 || isset($html)) 
            $message = htmlspecialchars($message, ENT_COMPAT | ENT_HTML401, cur_charset);

        if (isset($sig) && $userdata['uid'] != 1) 
            $message .= ' [addsig]';

        $message = af_cod($message);
        $message = smile($message);
        $message = make_clickable($message);
        $message = removeHack($message);
        $image_subject = '';

        $message = addslashes(dataimagetofileurl($message, 'modules/upload/upload/co'));

        $time = date("Y-m-d H:i:s", time() + ((int) Config::get('npds.gmt') * 3600));

        $sql = "INSERT INTO posts (post_idH, topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip, poster_dns) VALUES ('0', '$topic', '$image_subject', '$forum', '" . $userdata['uid'] . "', '$message', '$time', '$poster_ip', '$hostname')";
        
        if (!$result = sql_query($sql))
            forumerror('0020');
        else
            $IdPost = sql_last_id();

        $sql = "UPDATE users_status SET posts=posts+1 WHERE (uid = '" . $userdata['uid'] . "')";
        $result = sql_query($sql);

        if (!$result)
            forumerror('0029');

        // ordre de mise à jour d'un champ externe ?
        if ($comments_req_add != '')
            sql_query("UPDATE " . $comments_req_add);

        // envoi mail alerte
        if (Config::get('npds.notify')) {
            global $url_ret;

            $csubject = html_entity_decode(__d('comments', 'Nouveau commentaire'), ENT_COMPAT | ENT_HTML401, cur_charset) . ' ==> ' . Config::get('npds.nuke_url');
            $cmessage = '🔔 ' . __d('comments', 'Nouveau commentaire') . ' ==> <a href="' . Config::get('npds.nuke_url') . '/' . $url_ret . '">' . Config::get('npds.nuke_url') . '/' . $url_ret . '</a>';
            
            send_email(Config::get('npds.notify_email'), $csubject, $cmessage, Config::get('npds.notify_from'), false, "html", '');
        }

        redirect_url("$url_ret");
    } else {
        echo '
        <h2><i class="far fa-comment text-muted fa-lg me-2"></i>' . __d('comments', 'Commentaire') . '</h2>
        <hr />
        <div class="alert alert-danger" >' . __d('comments', 'Vous devez taper un message à poster.') . '</div>
        <p><a href="javascript:history.go(-1)" class="btn btn-primary">' . __d('comments', 'Retour en arrière') . '</a></p>';
    }
} else {
    // include('header.php');

    if ($allow_bbcode == 1)
        include("library/javascript/formhelp.java.php");

    echo '
    <h2><i class="far fa-comment text-muted fa-lg me-2"></i>' . __d('comments', 'Commentaire') . '</h2>
    <hr />';

    if ($formulaire == '')
        echo '<form action="modules.php" method="post" name="coolsus">';

    echo '<div class="mb-3 ">';

    $allow_to_reply = false;

    if ($forum_access == 0)
        $allow_to_reply = true;
    else
      if (isset($user))
        $allow_to_reply = true;

    if ($allow_to_reply) {
        if (isset($submitP)) {
            $time = date(__d('comments', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));

            if (isset($user)) {
                $userY = base64_decode($user);
                $userdata = explode(':', $userY);
                $userdata = get_userdata($userdata[1]);
            } else {
                $userdata = array('uid' => 1);
                $userdata = get_userdata($userdata['uid']);
            }

            $theposterdata = get_userdata_from_id($userdata['uid']);
            $messageP = $message;
            $messageP = af_cod($messageP);

            echo '
            <h4>' . __d('comments', 'Prévisualiser') . '</h4>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">';

            if (Config::get('npds.smilies')) {
                if ($theposterdata['user_avatar'] != '') {
                    if (stristr($theposterdata['user_avatar'], "users_private"))
                        $imgtmp = $theposterdata['user_avatar'];
                    else {
                        if ($ibid = theme_image("forum/avatar/" . $theposterdata['user_avatar'])) 
                            $imgtmp = $ibid;
                        else 
                            $imgtmp = "assets/images/forum/avatar/" . $theposterdata['user_avatar'];
                    }

                    echo '<a style="position:absolute; top:1rem;" tabindex="0" data-bs-toggle="popover" data-bs-html="true" data-bs-title="' . $theposterdata['uname'] . '" data-bs-content=\'' . member_qualif($theposterdata['uname'], $theposterdata['posts'], $theposterdata['rang']) . '\'><img class=" btn-secondary img-thumbnail img-fluid n-ava" src="' . $imgtmp . '" alt="' . $theposterdata['uname'] . '" /></a>';
                }
            }

            echo '
                &nbsp;<span style="position:absolute; left:6rem;" class="text-muted"><strong>' . $theposterdata['uname'] . '</strong></span>
            </div>
            <div class="card-body">
                <span class="text-muted float-end small" style="margin-top:-1rem;">' . __d('comments', 'Commentaires postés : ') . $time . '</span>
                <div id="post_preview" class="card-text pt-3">';

            $messageP = stripslashes($messageP);

            if (($forum_type == '6') or ($forum_type == '5'))
                highlight_string(stripslashes($messageP));
            else {
                if ($allow_bbcode) 
                    $messageP = smilie($messageP);

                if ($allow_sig == 1 and isset($sig))
                    $messageP .= '<div class="n-signature">' . nl2br($theposterdata['user_sig']) . '</div>';

                echo $messageP . '
                </div>';
            }

            echo '
                        </div>
                    </div>
                </div>
            </div>';
        } else
            $message = '';

        if ($formulaire != '') {
            echo '<div class="col">';
            include("modules/comments/comments_extender.php");
            echo '</div></div>';
        } else {
            if ($allow_bbcode)
                $xJava = 'name="message" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
            
            if (isset($citation) && !isset($submitP)) {

                $sql = "SELECT p.post_text, p.post_time, u.uname FROM posts p, users u WHERE post_id='$post' AND ((p.poster_id = u.uid) XOR (p.poster_id=0))";
                
                if ($r = sql_query($sql)) {
                    $m = sql_fetch_assoc($r);

                    $text = $m['post_text'];
                    $text = smile($text);
                    $text = str_replace('<br />', "\n", $text);
                    $text = stripslashes($text);
                    $text = desaf_cod($text);

                    $reply = ($m['post_time'] != '' && $m['uname'] != '') ?
                        '<div class="blockquote">' . __d('comments', 'Citation') . ' : <strong>' . $m['uname'] . '</strong>' . "\n" . $text . '</div>' :
                        $text . "\n";
                } else
                    $reply = __d('comments', 'Erreur de connexion à la base de données') . "\n";
            }

            if (!isset($reply)) 
                $reply = $message;

            echo '
            </div>
            <div class="mb-3 row">
                <label class="form-label" for="message">' . __d('comments', 'Message') . '</label>
                <div class="col-sm-12">
                    <div class="card">
                    <div class="card-header">
                        <div class="float-start">';

            putitems('ta_comment');

            echo '</div>';

            echo ($allow_html == 1) 
                ? '<span class="text-success float-end mt-2" title="HTML ' . __d('comments', 'Activé') . '" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>' . HTML_Add() 
                : '<span class="text-danger float-end mt-2" title="HTML ' . __d('comments', 'Désactivé') . '" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
            
            echo '
                    </div>
                    <div class="card-body">
                        <textarea id="ta_comment" class="form-control" ' . $xJava . ' name="message" rows="12">' . stripslashes($reply) . '</textarea>
                    </div>
                    <div class="card-footer p-0">
                        <span class="d-block">
                            <button class="btn btn-link" type="submit" value="' . __d('comments', 'Prévisualiser') . '" name="submitP" title="' . __d('comments', 'Prévisualiser') . '" data-bs-toggle="tooltip" ><i class="fa fa-eye fa-lg"></i></button>
                        </span>
                    </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="form-label">' . __d('comments', 'Options') . '</label>';

            if ($allow_html == 1) {
                if (isset($html)) 
                    $sethtml = 'checked="checked"';
                else 
                    $sethtml = '';

                echo '
                <div class="col-sm-12 my-2">
                    <div class="checkbox">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="html" name="html" ' . $sethtml . ' />
                        <label class="form-check-label" for="html">' . __d('comments', 'Désactiver le html pour cet envoi') . '</label>
                    </div>
                    </div>';
            }

            if ($user) {
                if ($allow_sig == 1 || isset($sig)) {
                    $asig = sql_query("SELECT attachsig FROM users_status WHERE uid='$cookie[0]'");
                    list($attachsig) = sql_fetch_row($asig);

                    if ($attachsig == 1 or isset($sig)) 
                        $s = 'checked="checked"';
                    else 
                        $s = '';

                    echo '
                    <div class="checkbox my-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sig" name="sig" ' . $s . ' />
                        <label class="form-check-label" for="sig"> ' . __d('comments', 'Afficher la signature') . '</label>
                    </div>
                    <span class="help-block"><small>' . __d('comments', 'Cela peut être retiré ou ajouté dans vos paramètres personnels') . '</small></span>
                    </div>';
                }
            }

            echo '</div>
            </div>';

            echo Q_spambot();
            echo '
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input type="hidden" name="ModPath" value="comments" />
                    <input type="hidden" name="ModStart" value="reply" />
                    <input type="hidden" name="topic" value="' . $topic . '" />
                    <input type="hidden" name="file_name" value="' . $file_name . '" />
                    <input type="hidden" name="archive" value="' . $archive . '" />
                    <input class="btn btn-primary" type="submit" name="submitS" value="' . __d('comments', 'Valider') . '" />
                    <input class="btn btn-danger" type="submit" name="cancel" value="' . __d('comments', 'Annuler la contribution') . '" />
                </div>
            </div>';
        }
    } else
        echo '<div class="alert alert-danger">' . __d('comments', 'Vous n\'êtes pas autorisé à participer à ce forum') . '</div>';

    if ($formulaire == '')
        echo '</form>';

    if ($allow_to_reply) {

        $post_aff = $Mmod ? '' : " AND post_aff='1' ";
        $sql = "SELECT * FROM posts WHERE topic_id='$topic'" . $post_aff . " AND forum_id='$forum' ORDER BY post_id DESC LIMIT 0,10";
        $result = sql_query($sql);

        if (sql_num_rows($result)) {
            echo __d('comments', 'Aperçu des sujets :');

            while ($myrow = sql_fetch_assoc($result)) {
                $posterdata = get_userdata_from_id($myrow['poster_id']);
                echo '
                <div class="card my-3">
                <div class="card-header">';

                if (Config::get('npds.smilies')) 
                    echo userpopover($posterdata['uname'], '48', 2);

                echo $posterdata['uname'];

                echo '<span class="float-end text-muted small">' . __d('comments', 'Posté : ') . convertdate($myrow['post_time']) . '</span>
                </div>
                <div class="card-body">';

                $posts = $posterdata['posts'];
                $message = stripslashes($myrow['post_text']);

                if ($allow_bbcode)
                    $message = smilie($message);

                // <a href in the message
                if (stristr($message, '<a href'))
                    $message = preg_replace('#_blank(")#i', '_blank\1 class=\1 \1', $message);

                $message = str_replace('[addsig]', '<div class="n-signature">' . nl2br($posterdata['user_sig']) . '</div>', $message);

                echo $message . '<br />
                </div>
                </div>';
            }
        }
    }
}

// include('footer.php');
