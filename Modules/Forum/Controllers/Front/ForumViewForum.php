<?php

namespace Modules\Forum\Controllers;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class ForumViewForum extends FrontController
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
            $cache_obj = new SuperCacheManager();
        else
            $cache_obj = new SuperCacheEmpty();
        
        include('auth.php');
        
        global $admin;
        
        //==> droits des admin sur les forums (superadmin et admin avec droit gestion forum)
        $adminforum = false;
        
        if ($admin) {
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
        
        settype($op, 'string');
        
        if (($op == "mark") and ($forum)) {
            if ($user) {
                $userX = base64_decode($user);
                $userR = explode(':', $userX);
        
                $resultT = sql_query("SELECT topic_id FROM forumtopics WHERE forum_id='$forum' ORDER BY topic_id ASC");
        
                $time_actu = time() + ((int) Config::get('npds.gmt') * 3600);
        
                while (list($topic_id) = sql_fetch_row($resultT)) {
                    $r = sql_query("SELECT rid FROM forum_read WHERE forum_id='$forum' AND uid='$userR[0]' AND topicid='$topic_id'");
        
                    if ($r) {
                        if (!list($rid) = sql_fetch_row($r))
                            $r = sql_query("INSERT INTO forum_read (forum_id, topicid, uid, last_read, status) VALUES ('$forum', '$topic_id', '$userR[0]', $time_actu, '1')");
                        else
                            $r = sql_query("UPDATE forum_read SET last_read='$time_actu', status='1' WHERE rid='$rid'");
                    }
                }
        
                header("location: forum.php");
            }
        }
        
        if ($forum == "index")
            header("location: forum.php");
        
        settype($forum, "integer");
        
        $rowQ1 = Q_Select("SELECT forum_name, forum_moderator, forum_type, forum_pass, forum_access, arbre FROM forums WHERE forum_id = '$forum'", 3600);
        
        if (!$rowQ1)
            forumerror('0002');
        
        $myrow = $rowQ1[0];
        
        $forum_name = stripslashes($myrow['forum_name']);
        $moderator = get_moderator($myrow['forum_moderator']);
        $forum_access = $myrow['forum_access'];
        
        if (($op == "solved") and ($topic_id) and ($forum) and ($sec_clef)) {
            if ($user) {
                $local_sec_clef = md5($forum . $topic_id . md5(Config::get('npds.Npds_Key')));
        
                if ($local_sec_clef == $sec_clef) {
                    $sqlS = "UPDATE forumtopics SET topic_status='2', topic_title='[" . __d('forum', 'Résolu') . "] - " . removehack($topic_title) . "' WHERE topic_id='$topic_id'";
                    
                    if (!$r = sql_query($sqlS))
                        forumerror('0011');
                }
                unset($local_sec_clef);
            }
            unset($sec_clef);
        }
        
        // Pour les forums de type Groupe, le Mot de Passe stock l'ID du groupe ...
        // Pour les forums de type Extended Text, le Mot de Passe stock le nom du fichier de formulaire ...
        if (($myrow['forum_type'] == 5) or ($myrow['forum_type'] == 7)) {
            $ok_affiche = false;
        
            if (isset($user)) {
                $tab_groupe = valid_group($user);
                $ok_affiche = groupe_forum($myrow['forum_pass'], $tab_groupe);
            }
        
            if ($ok_affiche)
                $Forum_passwd = $myrow['forum_pass'];
        }
        
        if ($myrow['forum_type'] == 8) 
            $Forum_passwd = $myrow['forum_pass'];
        else 
            settype($Forum_passwd, 'string');
        
        $hrefX = $myrow['arbre'] ? 'viewtopicH.php' : 'viewtopic.php';
        
        if (($myrow['forum_type'] == 1) and (($myrow['forum_name'] != $forum_name) or ($Forum_passwd != $myrow['forum_pass']))) {
        
            include('header.php');
        
            echo '
            <h3 class="mb-3">' . stripslashes($forum_name) . '</h3>
                <p class="lead">' . __d('forum', 'Modéré par : ') . '';
        
            $moderator_data = explode(' ', $moderator);
        
            for ($i = 0; $i < count($moderator_data); $i++) {
                $modera = get_userdata($moderator_data[$i]);
        
                if ($modera['user_avatar'] != '') {
                    if (stristr($modera['user_avatar'], "users_private"))
                        $imgtmp = $modera['user_avatar'];
                    else {
                        if ($ibid = theme_image("forum/avatar/" . $modera['user_avatar'])) {
                            $imgtmp = $ibid;
                        } else {
                            $imgtmp = "assets/images/forum/avatar/" . $modera['user_avatar'];
                        }
                    }
                }
        
                echo '<a href="user.php?op=userinfo&amp;uname=' . $moderator_data[$i] . '"><img width="48" height="48" class=" img-thumbnail img-fluid n-ava" src="' . $imgtmp . '" alt="' . $modera['uname'] . '" title="' . $modera['uname'] . '" data-bs-toggle="tooltip" /></a>';
            }
        
            echo '</p>';
            echo '
                <p class="lead">
                    <a href="forum.php">' . __d('forum', 'Index du forum') . '</a>&nbsp;&raquo;&raquo;&nbsp;' . stripslashes($forum_name) . '
                </p>
                <div class="card p-3">
                    <form id="privforumentry" action="viewforum.php" method="post">
                        <div class="mb-3 row">
                        <label class="col-form-label col-sm-12" for="forum_pass">' . __d('forum', 'Ceci est un forum privé. Vous devez entrer le mot de passe pour y accéder') . '</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="password" id="forum_pass" name="Forum_passwd"  placeholder="' . __d('forum', 'Mot de passe') . '" required="required"/>
                            <span class="help-block text-end" id="countcar_forum_pass"></span>
                        </div>
                        </div>
                        <input type="hidden" name="forum" value="' . $forum . '" />
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary me-2" name="submitpass" title="' . __d('forum', 'Valider') . '"><i class="fa fa-check me-1"></i>' . __d('forum', 'Valider') . '</button>
                            <button type="reset" class="btn btn-secondary" name="reset" title="' . __d('forum', 'Annuler') . '"><i class="fas fa-sync me-1"></i>' . __d('forum', 'Annuler') . '</button>
                        </div>
                    </form>
                </div>';
        
            $arg1 = '
                    var formulid=["privforumentry"];
                    inpandfieldlen("forum_pass",60);';
        
            adminfoot('fv', '', $arg1, '');
        
        } elseif (($Forum_passwd == $myrow['forum_pass']) or ($adminforum == 1)) {
            if (($myrow['forum_type'] == 9) and (!$user)) {
                header("location: forum.php");
            }
        
            $title = $forum_name;
        
            include('header.php');
        
            if ($user) {
                $userX = base64_decode($user);
                $userR = explode(':', $userX);
            }
        
            if ($solved) {
                if (isset($closoled)) {
                    $closol = "and topic_status='2'";
                    $mess_closoled = '<a href="viewforum.php?forum=' . $forum . '">' . __d('forum', 'Sans') . ' ' . __d('forum', 'Résolu') . '</a>';
                } else {
                    $closol = "and topic_status!='2'";
                    $mess_closoled = '<a href="viewforum.php?forum=' . $forum . '&amp;closoled=on">' . __d('forum', 'Seulement') . ' ' . __d('forum', 'Résolu') . '</a>';
                }
            } else {
                $closol = '';
                $mess_closoled = '';
            }
        
            echo '
            <p class="lead">
                <a href="forum.php" >' . __d('forum', 'Index du forum') . '</a>&nbsp;&raquo;&raquo;&nbsp;' . stripslashes($forum_name) . '
            </p>
            <h3 class="mb-3">';
        
            if ($forum_access != 9) {
                $allow_to_post = true;
        
                if ($forum_access == 2)
                    if (!user_is_moderator($userR[0], $userR[2], $forum_access)) 
                        $allow_to_post = false;
        
                if ($allow_to_post)
                    echo '<a href="newtopic.php?forum=' . $forum . '" title="' . __d('forum', 'Nouveau') . '"><i class="fa fa-plus-square me-2"></i><span class="d-none d-sm-inline">' . __d('forum', 'Nouveau sujet') . '<br /></span></a>';
            }
        
            echo stripslashes($forum_name) . '<span class="text-muted">&nbsp;#' . $forum . '</span>
            </h3>';
        
            $moderator_data = explode(' ', $moderator);
            $ibidcountmod = count($moderator_data);
        
            echo '
                <div class="card mb-3">
                    <div class="card-body p-2">
                        <div class="d-flex ">
                        <div class="badge bg-secondary align-self-center mx-2 col-2 col-md-3 col-xl-2 bg-white text-muted py-2 px-1"><span class="me-1 lead">' . $ibidcountmod . '<i class="fa fa-balance-scale fa-fw ms-1 d-inline d-md-none" title="' . __d('forum', 'Modérateur(s)') . '" data-bs-toggle="tooltip"></i></span><span class=" d-none d-md-inline">' . __d('forum', 'Modérateur(s)') . '</span></div>
                        <div class=" align-self-center me-auto">';
        
            $Mmod = false;
        
            for ($i = 0; $i < count($moderator_data); $i++) {
                $modera = get_userdata($moderator_data[$i]);
        
                if ($modera['user_avatar'] != '') {
                    if (stristr($modera['user_avatar'], 'users_private')) {
                        $imgtmp = $modera['user_avatar'];
                    } else {
                        if ($ibid = theme_image("forum/avatar/" . $modera['user_avatar'])) {
                            $imgtmp = $ibid;
                        } else {
                            $imgtmp = "assets/images/forum/avatar/" . $modera['user_avatar'];
                        }
                    }
                }
        
                if ($user)
                    if (($userR[1] == $moderator_data[$i])) {
                        $Mmod = true;
                    }
        
                echo '<a href="user.php?op=userinfo&amp;uname=' . $moderator_data[$i] . '"><img class=" img-thumbnail img-fluid n-ava-small me-1" src="' . $imgtmp . '" alt="' . $modera['uname'] . '" title="' . __d('forum', 'Modéré par : ') . ' ' . $modera['uname'] . '" data-bs-toggle="tooltip" /></a>';
            }
        
            echo '
                        </div>
                        </div>
                    </div>
                </div>';
        
            settype($start, "integer");
            settype($topics_per_page, "integer");
        
            $sql = "SELECT * FROM forumtopics WHERE forum_id='$forum' $closol ORDER BY topic_first,topic_time DESC LIMIT $start, $topics_per_page";
            
            if (!$result = sql_query($sql))
                forumerror('0004');
        
            if ($ibid = theme_image("forum/icons/red_folder.gif")) {
                $imgtmpR = $ibid;
            } else {
                $imgtmpR = "assets/images/forum/icons/red_folder.gif";
            }
        
            if ($ibid = theme_image("forum/icons/posticon.gif")) {
                $imgtmpP = $ibid;
            } else {
                $imgtmpP = "assets/images/forum/icons/posticon.gif";
            }
        
            if ($myrow = sql_fetch_assoc($result)) {
                echo '
                <h4 class="my-2">' . __d('forum', 'Sujets') . ' <span class="text-muted">' . $mess_closoled . '</span></h4>
                <table id ="lst_forum" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
                    <thead>
                        <tr>
                        <th class="n-t-col-xs-1" data-align="center"></th>
                        <th class="n-t-col-xs-1" data-align="center"></th>
                        <th class="" data-sortable="true" data-sorter="htmlSorter">' . __d('forum', 'Sujet') . '&nbsp;&nbsp;</th>
                        <th class="n-t-col-xs-1" class="text-center" data-sortable="true" data-align="right" ><i class="fa fa-reply fa-lg text-muted" title="' . __d('forum', 'Réponses') . '" data-bs-toggle="tooltip" ></i></th>
                        <th data-sortable="true" data-halign="center" data-align="left" ><i class="fa fa-user fa-lg text-muted" title="' . __d('forum', 'Emetteur') . '" data-bs-toggle="tooltip"></i></th>
                        <th class="n-t-col-xs-1" class="text-center" data-sortable="true" data-align="right" ><i class="fa fa-eye fa-lg text-muted" title="' . __d('forum', 'Lectures') . '" data-bs-toggle="tooltip" ></i></th>
                        <th data-align="right" >' . __d('forum', 'Dernières contributions') . '</th>
                        </tr>
                    </thead>
                    <tbody>';
        
                do {
                    echo '
                    <tr>';
        
                    $replys = get_total_posts($forum, $myrow['topic_id'], "topic", $Mmod);
                    $replys--;
        
                    if ($replys >= 0) {
        
                        if (Config::get('npds.smilies')) {
                            $rowQ1 = Q_Select("SELECT image FROM posts WHERE topic_id='" . $myrow['topic_id'] . "' AND forum_id='$forum' LIMIT 0,1", 86400);
                            $image_subject = $rowQ1[0]['image'];
                        }
        
                        settype($posts_per_page, 'integer');
        
                        if (($replys + 1) > $posts_per_page) {
                            $pages = 0;
        
                            for ($x = 0; $x < ($replys + 1); $x += $posts_per_page)
                                $pages++;
        
                            $last_post_url = "$hrefX?topic=" . $myrow['topic_id'] . "&amp;forum=$forum&amp;start=" . (($pages - 1) * $posts_per_page);
                        } else
                            $last_post_url = "$hrefX?topic=" . $myrow['topic_id'] . "&amp;forum=$forum";
        
                        if ($user) {
                            $sqlR = "SELECT rid FROM forum_read WHERE forum_id='$forum' AND uid='$userR[0]' AND topicid='" . $myrow['topic_id'] . "' AND status!='0'";
                            
                            if ($replys >= $hot_threshold)
                                $image = sql_num_rows(sql_query($sqlR)) == 0 ?
                                    '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-lg fa-file-alt faa-shake animated"></i></a>' :
                                    '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-lg fa-file-alt"></i></a>';
                            else
                                $image = sql_num_rows(sql_query($sqlR)) == 0 ?
                                    '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="far fa-lg fa-file-alt faa-shake animated"></i></a>' :
                                    '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="far fa-lg fa-file-alt"></i></a>';
                        } else
                            $image = ($replys >= $hot_threshold) ?
                                '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-lg fa-file-alt"></i></a>' :
                                '<a href="' . $last_post_url . '#lastpost" title="' . __d('forum', 'Dernières contributions') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="far fa-lg fa-file-alt"></i></a>';
        
                        if ($myrow['topic_status'] != 0)
                            $image = '<i class="fa fa-lg fa-lock text-danger"></i>';
        
                        echo '
                        <td>' . $image . '</td>';
        
                        if ($image_subject != '') {
                            if ($ibid = theme_image("forum/subject/$image_subject")) {
                                $imgtmp = $ibid;
                            } else {
                                $imgtmp = "assets/images/forum/subject/$image_subject";
                            }
        
                            echo '<td><img class="n-smil" src="' . $imgtmp . '" alt="" /></td>';
                        } else
                            echo '<td><img class="n-smil" src="' . $imgtmpP . '" alt="" /></td>';
        
                        $topic_title = stripslashes($myrow['topic_title']);
        
                        if (!stristr($topic_title, '<a href=')) {
                            $last_post_url = "$hrefX?topic=" . $myrow['topic_id'] . "&amp;forum=$forum";
                            echo '<td><a href="' . $last_post_url . '" >' . ucfirst($topic_title) . '</a></td>';
                            $Sredirection = false;
                        } else {
                            echo '
                            <td>' . $topic_title . '</td>';
                            $Sredirection = true;
                        }
        
                        if ($Sredirection)
                            echo '<td>&nbsp;</td>';
                        else
                            echo '<td>' . $replys . '</td>';
        
                        if ($Sredirection) {
                            if (!$Mmod) {
                                echo '<td>&nbsp;</td>';
                            } else {
                                echo "<td>[ <a href=\"$hrefX?topic=" . $myrow['topic_id'] . "&amp;forum=$forum\">" . __d('forum', 'Editer') . "</a> ]</td>";
                            }
        
                            echo '<td>&nbsp;</td>';
                        } else {
                            if ($myrow['topic_poster'] == 1)
                                echo '<td></td>';
                            else {
                                $rowQ1 = Q_Select("SELECT uname FROM users WHERE uid='" . $myrow['topic_poster'] . "'", 3600);
                                
                                if ($rowQ1) {
                                    echo '<td>' . userpopover($rowQ1[0]['uname'], 40, 2) . $rowQ1[0]['uname'] . '</td>';
                                } else
                                    echo '<td>' . Config::get('npds.anonymous') . '</td>';
                            }
        
                            echo '<td>' . $myrow['topic_views'] . '</td>';
                        }
        
                        if ($Sredirection)
                            echo '
                                <td>&nbsp;</td>
                            </tr>';
                        else
                            echo '
                                <td class="small">' . get_last_post($myrow['topic_id'], "topic", "infos", $Mmod) . '</td>
                            </tr>';
                    }
        
                } while ($myrow = sql_fetch_assoc($result));
        
                sql_free_result($result);
        
                echo '
                    </tbody>
                </table>';
        
                if ($user)
                    echo '<p class="mt-1"><a href="viewforum.php?op=mark&amp;forum=' . $forum . '"><i class="far fa-check-square fa-lg"></i></a>&nbsp;' . __d('forum', 'Marquer tous les messages comme lus') . '</p>';
            } else {
                if ($forum_access != 9)
                    echo '
                    <div class="alert alert-danger my-3">' . __d('forum', 'Il n\'y a aucun sujet pour ce forum.') . '<br /><a href="newtopic.php?forum=' . $forum . '" >' . __d('forum', 'Vous pouvez en poster un ici.') . '</a></div>';
            }
        
            $sql = "SELECT COUNT(*) AS total FROM forumtopics WHERE forum_id='$forum' $closol";
        
            if (!$r = sql_query($sql)) 
                forumerror('0001');
        
            list($all_topics) = sql_fetch_row($r);
            sql_free_result($r);
        
            if (isset($closoled)) 
                $closol = '&amp;closoled=on';
            else 
                $closol = '';
        
            $count = 1;
            $nbPages = ceil($all_topics / $topics_per_page);
        
            $current = 1;
            if ($start >= 1) {
                $current = $start / $topics_per_page;
            } else if ($start < 1) {
                $current = 0;
            } else {
                $current = $nbPages;
            }
        
            echo '<div class="mb-2"></div>' . paginate('viewforum.php?forum=' . $forum . '&amp;start=', $closol, $nbPages, $current, 1, $topics_per_page, $start);
        
            echo searchblock();
        
            echo '<blockquote class="blockquote my-3">';
        
            if ($user)
                echo '
                <i class="far fa-file-alt fa-lg faa-shake animated text-primary"></i> = ' . __d('forum', 'Les nouvelles contributions depuis votre dernière visite.') . '<br />
                <i class="fas fa-file-alt fa-lg faa-shake animated text-primary"></i> = ' . __d('forum', 'Plus de') . ' ' . $hot_threshold . ' ' . __d('forum', 'Contributions') . '<br />
                <i class="far fa-file-alt fa-lg text-primary"></i> = ' . __d('forum', 'Aucune nouvelle contribution depuis votre dernière visite.') . '<br />
                <i class="fas fa-file-alt fa-lg text-primary"></i> = ' . __d('forum', 'Plus de') . ' ' . $hot_threshold . ' ' . __d('forum', 'Contributions') . '<br />';
            else
                echo '
                <i class="fas fa-file-alt fa-lg text-primary"></i> = ' . __d('forum', 'Plus de') . ' ' . $hot_threshold . ' ' . __d('forum', 'Contributions') . '<br />
                <i class="far fa-file-alt fa-lg text-primary"></i> = ' . __d('forum', 'Contributions') . '.<br />';
            
            echo '
                <i class="fa fa-lock fa-lg text-danger"></i> = ' . __d('forum', 'Ce sujet est verrouillé : il ne peut accueillir aucune nouvelle contribution.') . '<br />
            </blockquote>';
        
            if ($SuperCache) {
                $cache_clef = "forum-jump-to";
                $CACHE_TIMINGS[$cache_clef] = 3600;
                $cache_obj->startCachingBlock($cache_clef);
            }
        
            if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
        
                echo '
                <form class="my-3" action="viewforum.php" method="post">
                    <div class="mb-3 row">
                        <div class="col-12">
                            <label class="visually-hidden" for="forum">' . __d('forum', 'Sauter à : ') . '</label>
                            <select class="form-select" name="forum" onchange="submit();">
                            <option value="index">' . __d('forum', 'Sauter à : ') . '</option>
                            <option value="index">' . __d('forum', 'Index du forum') . '</option>';
        
                $sub_sql = "SELECT forum_id, forum_name, forum_type, forum_pass FROM forums ORDER BY cat_id,forum_index,forum_id";
        
                if ($res = sql_query($sub_sql)) {
                    while (list($forum_id, $forum_name, $forum_type, $forum_pass) = sql_fetch_row($res)) {
                        if (($forum_type != '9') or ($userdata)) {
                            if (($forum_type == '7') or ($forum_type == '5')) {
                                $ok_affich = false;
                            } else {
                                $ok_affich = true;
                            }
        
                            if ($ok_affich) 
                                echo '<option value="' . $forum_id . '">&nbsp;&nbsp;' . stripslashes($forum_name) . '</option>';
                        }
                    }
                }
        
                echo '
                            </select>
                        </div>
                    </div>
                </form>';
        
                include("footer.php");
            }
            
            if ($SuperCache) {
                $cache_obj->endCachingBlock($cache_clef);
            }
        } else {
            header("location: forum.php");
        }
    
        
    }

}