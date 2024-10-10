<?php

namespace Modules\Forum\Controllers;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class ForumEditpost extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
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
     * @return void
     */
    public function index()
    {
        if ($SuperCache)
            $cache_obj = new cacheManager();
        else
            $cache_obj = new SuperCacheEmpty();
        
        include("auth.php");
        
        
        
        $rowQ1 = Q_Select("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM forums WHERE forum_id = '$forum'", 3600);
        if (!$rowQ1)
            forumerror('0001');
        
        $myrow = $rowQ1[0];
        
        $forum_type = $myrow['forum_type'];
        $forum_access = $myrow['forum_access'];
        $moderator = get_moderator($myrow['forum_moderator']);
        
        if (isset($user)) {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
            $moderator = explode(' ', $moderator);
            $Mmod = false;
        
            for ($i = 0; $i < count($moderator); $i++) {
                if (($userdata[1] == $moderator[$i])) {
                    $Mmod = true;
                    break;
                }
            }
        }
        
        settype($submitS, 'string');
        
        if ($submitS) {
            include("header.php");
        
            $sql = "SELECT poster_id, topic_id FROM posts WHERE post_id = '$post_id'";
            $result = sql_query($sql);
        
            if (!$result)
                forumerror('0022');
        
            $row = sql_fetch_assoc($result);
        
            if ($userdata[0] == $row['poster_id'])
                $ok_maj = true;
            else {
                if (!$Mmod) {
                    forumerror('0035');
                }
        
                if ((user_is_moderator($userdata[0], $userdata[2], $forum_access) < 2)) {
                    forumerror('0036');
                }
            }
        
            $userdata = get_userdata($userdata[1]);
        
            if ($allow_html == 0 || isset($html))
                $message = htmlspecialchars($message, ENT_COMPAT | ENT_HTML401, cur_charset);
        
            if (($allow_bbcode == 1) and ($forum_type != '6') and ($forum_type != '5'))
                $message = smile($message);
        
            if (($forum_type != 6) and ($forum_type != 5)) {
                $message = make_clickable($message);
                $message = af_cod($message);
                $message = str_replace("\n", "<br />", removeHack($message));
                $message .= '<div class="text-muted text-end small"><i class="fa fa-edit"></i>&nbsp;' . __d('forum', 'Message édité par') . " : " . $userdata['uname'] . " / " . post_convertdate(time() + ((int) Config::get('npds.gmt') * 3600)) . '</div>';
            } else {
                $message .= "\n\n" . __d('forum', 'Message édité par') . " : " . $userdata['uname'] . " / " . post_convertdate(time() + ((int) Config::get('npds.gmt') * 3600));
            }
        
            $message = addslashes($message);
        
            if ($subject == '') 
                $subject = __d('forum', 'Sans titre');
        
            // Forum ARBRE
            if ($arbre)
                $hrefX = 'viewtopicH.php';
            else
                $hrefX = 'viewtopic.php';
        
            if (!isset($delete)) {
                $sql = "UPDATE posts SET post_text = '$message', image='$image_subject' WHERE (post_id = '$post_id')";
                if (!$result = sql_query($sql))
                    forumerror('0001');
        
                $sql = "UPDATE forum_read SET status='0' WHERE topicid = '" . $row['topic_id'] . "'";
                if (!$r = sql_query($sql))
                    forumerror('0001');
        
                $sql = "UPDATE forumtopics SET topic_title = '$subject', topic_time = '" . date("Y-m-d H:i:s", time() + ((int) Config::get('npds.gmt') * 3600)) . "', current_poster='" . $userdata['uid'] . "' WHERE topic_id = '" . $row['topic_id'] . "'";
                if (!$result = sql_query($sql))
                    forumerror('0020');
        
                redirect_url("$hrefX?topic=" . $row['topic_id'] . "&forum=$forum");
            } else {
                $indice = sql_num_rows(sql_query("SELECT post_id FROM posts WHERE post_idH='$post_id'"));
        
                if (!$indice) {
                    $sql = "DELETE FROM posts WHERE post_id='$post_id'";
        
                    if (!$r = sql_query($sql))
                        forumerror('0001');
        
                    control_efface_post("forum_App", $post_id, "", "");
        
                    if (get_total_posts($forum, $row['topic_id'], "topic", $Mmod) == 0) {
                        $sql = "DELETE FROM forumtopics WHERE topic_id = '" . $row['topic_id'] . "'";
                        if (!$r = sql_query($sql))
                            forumerror('0001');
        
                        $sql = "DELETE FROM forum_read WHERE topicid = '" . $row['topic_id'] . "'";
                        @sql_query($sql);
                        redirect_url("viewforum.php?forum=$forum");
        
                        die();
                    } else {
                        $result = sql_query("SELECT post_time, poster_id FROM posts WHERE topic_id='" . $row['topic_id'] . "' ORDER BY post_id DESC LIMIT 0,1");
                        $rowX = sql_fetch_row($result);
                        $sql = "UPDATE forumtopics SET topic_time = '$rowX[0]', current_poster='$rowX[1]' WHERE topic_id = '" . $row['topic_id'] . "'";
                        
                        if (!$r = sql_query($sql))
                            forumerror('0001');
                    }
                    redirect_url("$hrefX?topic=" . $row['topic_id'] . "&forum=$forum");
                } else
                    echo '<div class="alert alert-danger">' . __d('forum', 'Votre contribution n\'a pas été supprimée car au moins un post est encore rattaché (forum arbre).') . '</div>';
            }
        } else {
            include("header.php");
        
            if ($allow_bbcode == 1)
                include("library/javascript/formhelp.java.php");
        
            $sql = "SELECT p.*, u.uname, u.uid, u.user_sig FROM posts p, users u WHERE (p.post_id = '$post_id') AND ((p.poster_id = u.uid) XOR (p.poster_id=0))";
            if (!$result = sql_query($sql))
                forumerror('0001');
        
            $myrow = sql_fetch_assoc($result);
        
            if ((!$Mmod) and ($userdata[0] != $myrow['uid'])) {
                forumerror('0035');
            }
        
            if (!$result = sql_query("SELECT topic_title, topic_status FROM forumtopics WHERE topic_id='" . $myrow['topic_id'] . "'")) {
                forumerror('0001');
            } else {
                list($title, $topic_status) = sql_fetch_row($result);
                
                if (($topic_status != 0) and !$Mmod)
                    forumerror('0025');
            }
        
            settype($submitP, 'string');
        
            if ($submitP) {
                $acc = 'editpost';
                $title = stripslashes($subject);
                $message = stripslashes(make_clickable($message));
        
                include("preview.php");
            } else {
                $image_subject = $myrow['image'];
                $title = stripslashes($title);
                $message = $myrow['post_text'];
        
                if (($forum_type != 6) and ($forum_type != 5)) {
                    $message = str_replace("<br />", "\n", $message);
                    $message = smile($message);
                    $message = desaf_cod($message);
                    $message = undo_htmlspecialchars($message, ENT_COMPAT | ENT_HTML401, cur_charset);
                } else
                    $message = htmlspecialchars($message, ENT_COMPAT | ENT_HTML401, cur_charset);
        
                $message = stripslashes($message);
            }
        
            if ((($Mmod) or ($userdata[0] == $myrow['uid'])) and ($forum_access != 9)) {
                $qui = $myrow['poster_id'] == 0 ? Config::get('npds.anonymous') : $myrow['uname'];
        
                echo '
                <div>
                <h3>' . __d('forum', 'Edition de la soumission') . ' de <span class="text-muted">' . $qui . '</span></h3>
                <hr />
                <form action="editpost.php" method="post" name="coolsus">';
        
                if ($Mmod)
                    echo '
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-12" for="subject">' . __d('forum', 'Titre') . '</label>
                        <div class="col-sm-12">
                        <input class="form-control" type="text" id="subject" name="subject" maxlength="100" value="' . htmlspecialchars($title, ENT_COMPAT | ENT_HTML401, cur_charset) . '" />
                        </div>
                    </div>';
                else {
                    echo '<strong>' . __d('forum', 'Edition de la soumission') . '</strong> : ' . $title;
                    echo "<input type=\"hidden\" name=\"subject\" value=\"" . htmlspecialchars($title, ENT_COMPAT | ENT_HTML401, cur_charset) . "\" />";
                }
            } else
                forumerror('0036');
        
            if (Config::get('npds.smilies')) {
                echo '
                <div class="d-none d-sm-block mb-3 row">
                    <label class="col-form-label col-sm-12">' . __d('forum', 'Icone du message') . '</label>
                    <div class="col-sm-12">
                        <div class="border rounded pt-2 px-2 n-fond_subject">
                        ' . emotion_add($image_subject) . '
                        </div>
                    </div>
                </div>';
            }
        
            echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="message">' . __d('forum', 'Message') . '</label>';
        
            if ($allow_bbcode)
                $xJava = ' onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" onfocus="storeForm(this)"';
        
            echo '
                    <div class="col-sm-12">
                        <div class="card">
                        <div class="card-header">
                            <div class="float-start">';
        
            putitems('ta_edipost');
        
            echo '</div>';
        
            if ($allow_html == 1)
                echo '<span class="text-success float-end mt-2" title="HTML ' . __d('forum', 'Activé') . '" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>' . HTML_Add();
            else
                echo '<span class="text-danger float-end mt-2" title="HTML ' . __d('forum', 'Désactivé') . '" data-bs-toggle="tooltip"><i class="fa fa-code fa-lg"></i></span>';
            
            echo '
                        </div>
                        <div class="card-body">
                            <textarea id="ta_edipost" class="form-control" ' . $xJava . ' name="message" rows="10" cols="60">' . $message . '</textarea>
                        </div>
                        <div class="card-footer p-0">
                            <span class="d-block">
                                <button class="btn btn-link" type="submit" value="' . __d('forum', 'Prévisualiser') . '" name="submitP" title="' . __d('forum', 'Prévisualiser') . '" data-bs-toggle="tooltip" ><i class="fa fa-eye fa-lg"></i></button>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>';
        
            if (($allow_html == 1) and ($forum_type != 6)) {
                if (isset($html))
                    $sethtml = 'checked="checked"';
                else
                    $sethtml = '';
        
                echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12">' . __d('forum', 'Options') . '</label>
                    <div class="col-sm-12">
                        <div class="checkbox">
                            <div class="form-check text-danger">
                            <input class="form-check-input" type="checkbox" id="delete_p" name="delete" />
                            <label class="form-check-label" for="delete_p">' . __d('forum', 'Supprimer ce message') . '</label>
                            </div>
                        </div>
                        <div class="checkbox">
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="html" name="html" ' . $sethtml . ' />
                            <label class="form-check-label" for="html">' . __d('forum', 'Désactiver le html pour cet envoi') . '</label>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        
            echo '
                <input type="hidden" name="post_id" value="' . $post_id . '" />
                <input type="hidden" name="forum" value="' . $forum . '" />
                <input type="hidden" name="topic_id" value="' . $topic . '" />
                <input type="hidden" name="topic" value="' . $topic . '" />
                <input type="hidden" name="arbre" value="' . $arbre . '" />
                <div class="mb-3 row">
                    <div class="col-sm-12 ms-sm-auto ">
                        <button class="btn btn-primary" type="submit" name="submitS" value="' . __d('forum', 'Valider') . '" >' . __d('forum', 'Valider') . '</button>&nbsp;
                    </div>
                </div>
            </form>
            </div>';
        }
        
        include("footer.php");
    
    }

}