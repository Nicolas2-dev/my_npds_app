<?php

namespace Modules\Forum\Controllers;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class ForumTopicAdmin extends FrontController
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
        // if ($SuperCache)
        //     $cache_obj = new SuperCacheManager();
        // else
        //     $cache_obj = new SuperCacheEmpty();

        include('auth.php');

        global $adminforum;

        //==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
        $adminforum = false;

        if ($admin) {
            $adminforum = 0;
            $adminX = base64_decode($admin);
            $adminR = explode(':', $adminX);

            $Q = sql_fetch_assoc(sql_query("SELECT * FROM authors WHERE aid='$adminR[0]' LIMIT 1"));

            if ($Q['radminsuper'] == 1) {
                $adminforum = 1;
            } else {
                $R = sql_query("SELECT fnom, fid, radminsuper FROM authors a LEFT JOIN droits d ON a.aid = d.d_aut_aid LEFT JOIN fonctions f ON d.d_fon_fid = f.fid WHERE a.aid='$adminR[0]' AND f.fid BETWEEN 13 AND 15");
                
                if (sql_num_rows($R) >= 1) 
                    $adminforum = 1;
            }
        }
        //<== droits des admin sur les forums (superadmin et admin avec droit gestion forum)

        if (isset($arbre) and ($arbre == '1')) 
            $url_ret = "viewtopicH.php";
        else 
            $url_ret = "viewtopic.php";

        //   if($mode!='viewip') {
        $Mmod = false;
        if (isset($user)) {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);

            settype($forum, 'integer');

            $rowQ1 = Q_Select("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM forums WHERE forum_id = '$forum'", 3600);
            
            if (!$rowQ1)
                forumerror('0001');

            $myrow = $rowQ1[0];
            $moderator = explode(' ', get_moderator($myrow['forum_moderator']));

            for ($i = 0; $i < count($moderator); $i++) {
                if (($userdata[1] == $moderator[$i])) {
                    if (user_is_moderator($userdata[0], $userdata[2], $myrow['forum_access']))
                        $Mmod = true;
                    break;
                }
            }
        }

        if ((!$Mmod) and ($adminforum == 0))
            forumerror('0007');
        //   }
        if ((isset($submit)) and ($mode == 'move')) {
            $sql = "UPDATE forumtopics SET forum_id='$newforum' WHERE topic_id='$topic'";

            if (!$r = sql_query($sql))
                forumerror('0010');

            $sql = "UPDATE posts SET forum_id='$newforum' WHERE topic_id='$topic' AND forum_id='$forum'";

            if (!$r = sql_query($sql))
                forumerror('0010');

            $sql = "DELETE FROM forum_read where topicid='$topic'";

            if (!$r = sql_query($sql))
                forumerror('0001');

            $sql = "UPDATE $upload_table SET forum_id='$newforum' WHERE apli='forum_App' AND topic_id='$topic' AND forum_id='$forum'";
            sql_query($sql);

            $sql = "SELECT arbre FROM forums WHERE forum_id='$newforum'";
            $arbre = sql_fetch_assoc(sql_query($sql));

            if ($arbre['arbre']) 
                $url_ret = "viewtopicH.php";
            else 
                $url_ret = "viewtopic.php";

            include("header.php");

            echo '
                <div class="alert alert-success">
                <h4 class="alert-heading">' . __d('forum', 'Le sujet a été déplacé.') . '</h4>
                <hr /><a href="' . $url_ret . '?topic=' . $topic . '&amp;forum=' . $newforum . '" class="alert-link">' . __d('forum', 'Cliquez ici pour voir le nouveau sujet.') . '</a><br /><a href="forum.php" class="alert-link">' . __d('forum', 'Cliquez ici pour revenir à l\'index des Forums.') . '</a>
                </div>';

            Q_Clean();

            include("footer.php");
        } else {
            if ((isset($Mmod) and $Mmod === true) or ($adminforum == 1)) {
                switch ($mode) {

                    case 'move':
                        include("header.php");

                        echo '
                        <h2>' . __d('forum', 'Forum') . '</h2>
                        <form action="topicadmin.php" method="post">
                            <div class="mb-3 row">
                                <label class="form-label" for="newforum">' . __d('forum', 'Déplacer le sujet vers : ') . '</label>
                                <div class="col-sm-12">
                                <select class="form-select" name="newforum">';

                        $sql = "SELECT forum_id, forum_name FROM forums WHERE forum_id!='$forum' ORDER BY cat_id,forum_index,forum_id";
                        
                        if ($result = sql_query($sql)) {
                            if ($myrow = sql_fetch_assoc($result)) {
                                do {
                                    echo '<option value="' . $myrow['forum_id'] . '">' . $myrow['forum_name'] . '</option>';
                                } while ($myrow = sql_fetch_assoc($result));
                            } else {
                                echo '<option value="-1">' . __d('forum', 'Plus de forum') . '</option>';
                            }
                        } else {
                            echo '<option value="-1">Database Error</option>';
                        }

                        echo '
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                <input type="hidden" name="mode" value="move" />
                                <input type="hidden" name="topic" value="' . $topic . '" />
                                <input type="hidden" name="forum" value="' . $forum . '" />
                                <input type="hidden" name="arbre" value="' . $arbre . '" />
                                <input class="btn btn-primary" type="submit" name="submit" value="' . __d('forum', 'Déplacer le sujet') . '" />
                                </div>
                            </div>
                        </form>';

                        include("footer.php");
                        break;
                    case 'del':
                        $sql = "DELETE FROM posts WHERE topic_id='$topic' AND forum_id='$forum'";

                        if (!$result = sql_query($sql))
                            forumerror('0009');

                        $sql = "DELETE FROM forumtopics WHERE topic_id='$topic'";

                        if (!$result = sql_query($sql))
                            forumerror('0010');

                        $sql = "DELETE FROM forum_read WHERE topicid='$topic'";

                        if (!$r = sql_query($sql))
                            forumerror('0001');

                        control_efface_post("forum_App", "", $topic, "");
                        header("location: viewforum.php?forum=$forum");
                        break;

                    case 'lock':
                        $sql = "UPDATE forumtopics SET topic_status=1 WHERE topic_id='$topic'";

                        if (!$r = sql_query($sql))
                            forumerror('0011');

                        header("location: $url_ret?topic=$topic&forum=$forum");
                        break;

                    case 'unlock':
                        $topic_title = '';
                        $sql = "SELECT topic_title FROM forumtopics WHERE topic_id = '$topic'";
                        $r = sql_fetch_assoc(sql_query($sql));

                        $topic_title = str_replace("[" . __d('forum', 'Résolu') . "] - ", "", $r['topic_title']);
                        $sql = "UPDATE forumtopics SET topic_status = '0', topic_first='1', topic_title='" . addslashes($topic_title) . "' WHERE topic_id = '$topic'";
                        
                        if (!$r = sql_query($sql))
                            forumerror('0012');

                        header("location: $url_ret?topic=$topic&forum=$forum");
                        break;

                    case 'first':
                        $sql = "UPDATE forumtopics SET topic_status = '1', topic_first='0' WHERE topic_id = '$topic'";
                        
                        if (!$r = sql_query($sql))
                            forumerror('0011');

                        header("location: $url_ret?topic=$topic&forum=$forum");
                        break;

                    case 'viewip':
                        include("header.php");
                        include('modules/geoloc/geoloc_locip.php');

                        $sql = "SELECT u.uname, p.poster_ip, p.poster_dns FROM users u, posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
                        
                        if (!$r = sql_query($sql))
                            forumerror('0013');

                        if (!$m = sql_fetch_assoc($r))
                            forumerror('0014');

                        echo '
                        <h2 class="mb-3">' . __d('forum', 'Forum') . '</h2>
                        <div class="card card-body mb-3">
                            <h3 class="card-title mb-3" >' . __d('forum', 'Adresses IP et informations sur les utilisateurs') . '</h3>
                            <div class="row">
                                <div class="col mb-3">
                                <span class="text-muted">' . __d('forum', 'Identifiant : ') . '</span><span class="">' . $m['uname'] . '</span><br />
                                <span class="text-muted">' . __d('forum', 'Adresse IP de l\'utilisateur : ') . '</span><span class="">' . $m['poster_ip'] . ' => <a class="text-danger" href="topicadmin.php?mode=banip&topic=' . $topic . '&post=' . $post . '&forum=' . $forum . '&arbre=' . $arbre . '" >' . __d('forum', 'Bannir cette @Ip') . '</a></span><br />
                                <span class="text-muted">' . __d('forum', 'Adresse DNS de l\'utilisateur : ') . '</span><span class="">' . $m['poster_dns'] . '</span><br />
                                <span class="text-muted">GeoTool : </span><span class=""><a href="http://www.ip-tracker.org/?ip=' . $m['poster_ip'] . '" target="_blank" >IP tracker</a><br />
                                </div>';

                        echo localiser_ip($iptoshow = $m['poster_ip']);

                        echo '
                            </div>
                        </div>
                        <a href="' . $url_ret . '?topic=' . $topic . '&amp;forum=' . $forum . '" class="btn btn-secondary">' . __d('forum', 'Retour en arrière') . '</a>';

                        include("footer.php");
                        break;

                    case 'banip':
                        $sql = "SELECT p.poster_ip FROM users u, posts p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
                        
                        if (!$r = sql_query($sql))
                            forumerror('0013');

                        if (!$m = sql_fetch_assoc($r))
                            forumerror('0014');

                        L_spambot($m['poster_ip'], "ban");
                        header("location: $url_ret?topic=$topic&forum=$forum");
                        break;

                    case 'aff':
                        $sql = "UPDATE posts SET post_aff = '$ordre' WHERE post_id = '$post'";
                        sql_query($sql);

                        header("location: $url_ret?topic=$topic&forum=$forum");
                        break;
                }
            } else {
                include("header.php");

                echo '
                    <div class="alert alert-danger">' . __d('forum', 'Vous n\'êtes pas identifié comme modérateur de ce forum. Opération interdite.') . '<br />
                        <a class="btn btn-secondary" href="javascript:history.go(-1)" >' . __d('forum', 'Go Back') . '</a>
                    </div>';

                include("footer.php");
            }
        }   
    }

}