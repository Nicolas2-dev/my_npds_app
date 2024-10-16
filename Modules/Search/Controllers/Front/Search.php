<?php

namespace Modules\Search\Controllers\Front;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Modules\News\Support\Facades\News;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Date;
use Modules\Npds\Support\Facades\Hack;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;


class Search extends FrontController
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
        $offset = 25;
        $limit_full_search = 250;
        
        if (!isset($min)) {
            $min = 0;
        }
        
        if (!isset($max)) {
            $max = $min + $offset;
        }
        
        if (!isset($member)) {
            $member = '';
        }
        
        if (!isset($query)) {
            $query_title = '';
            $query_body = '';
            
            $query = $query_body;
            $limit = " LIMIT 0, $limit_full_search";
        } else {
            $query_title = Hack::remove(stripslashes(urldecode($query))); // electrobug
            $query_body = Hack::remove(stripslashes(htmlentities(urldecode($query), ENT_NOQUOTES, cur_charset))); // electrobug
            
            $query = $query_body;
            $limit = '';
        }
        
        if ($topic > 0) {
            $result = sql_query("SELECT topicimage, topictext FROM topics WHERE topicid='$topic'");
            list($topicimage, $topictext) = sql_fetch_row($result);
        } else {
            $topictext = __d('search', 'Tous les sujets');
            $topicimage = "all-topics.gif";
        }
        
        if ($type == 'users') {
            echo '<h2 class="mb-3">' . __d('search', 'Rechercher dans la base des utilisateurs') . '</h2><hr />';
        } elseif ($type == 'sections') {
            echo '<h2 class="mb-3">' . __d('search', 'Rechercher dans les rubriques') . '</h2><hr />';
        } elseif ($type == 'reviews') {
            echo '<h2 class="mb-3">' . __d('search', 'Rechercher dans les critiques') . '</h2><hr />';
        } elseif ($type == 'archive') {
            echo '<h2 class="mb-3">' . __d('search', 'Rechercher dans') . ' <span class="text-lowercase">' . __d('search', 'Archives') . '</span></h2><hr />';
        } else {
            echo '<h2 class="mb-3">' . __d('search', 'Rechercher dans') . ' ' . Language::aff_langue($topictext) . '</h2><hr />';
        }

        echo '<form action="search.php" method="get">';
            
        /*
        if (($type == 'users') OR ($type == 'sections') OR ($type == 'reviews')) {
            echo "<img src=\"".Config::get('npds.tipath')."all-topics.gif\" align=\"left\" border=\"0\" alt=\"\" />";
        } else {
            if ((($topicimage) or ($topicimage!="")) and (file_exists("Config::get('npds.tipath')$topicimage"))) {
                echo "<img src=\"Config::get('npds.tipath')$topicimage\" align=\"right\" border=\"0\" alt=\"".Language::aff_langue($topictext)."\" />";
            }
        }
        */
        
        echo '
            <div class="mb-3">
                <input class="form-control" type="text" name="query" value="' . $query . '" />
            </div>';
        
        $toplist = sql_query("SELECT topicid, topictext FROM topics ORDER BY topictext");
        
        echo '
            <div class="mb-3">
                <select class="form-select" name="topic">
                    <option value="">' . __d('search', 'Tous les sujets') . '</option>';
        
        $sel = '';
        while (list($topicid, $topics) = sql_fetch_row($toplist)) {
            if ($topicid == $topic) {
                $sel = 'selected="selected" ';
            }
        
            echo '<option ' . $sel . ' value="' . $topicid . '">' . substr_replace(Language::aff_langue($topics), '...', 25, -1) . '</option>';
        
            $sel = '';
        }
        
        echo '
                </select>
            </div>
            <div class="mb-3">
                <select class="form-select" name="category">
                    <option value="0">' . __d('search', 'Articles') . '</option>';
        
        $catlist = sql_query("SELECT catid, title FROM stories_cat ORDER BY title");
        
        $sel = '';
        while (list($catid, $title) = sql_fetch_row($catlist)) {
            if ($catid == $category) {
                $sel = 'selected="selected" ';
            }
        
            echo '<option ' . $sel . ' value="' . $catid . '">' . Language::aff_langue($title) . '</option>';
        
            $sel = '';
        }
        
        echo '
                </select>
            </div>';
        
        $thing = sql_query("SELECT aid FROM authors ORDER BY aid");
        
        echo '
            <div class="mb-3">
                <select class="form-select" name="author">
                    <option value="">' . __d('search', 'Tous les auteurs') . '</option>';
        
        $sel = '';
        while (list($authors) = sql_fetch_row($thing)) {
            if ($authors == $author) {
                $sel = 'selected="selected" ';
            }
        
            echo '<option ' . $sel . ' value="' . $authors . '">' . $authors . '</option>';
        
            $sel = '';
        }
        
        echo '
                </select>
            </div>';
         
        $sel1 = '';
        $sel2 = '';
        $sel3 = '';
        $sel4 = '';
        $sel5 = '';
        $sel6 = '';
        
        if ($days == '0') {
            $sel1 = 'selected="selected"';
        } elseif ($days == "7") {
            $sel2 = 'selected="selected"';
        } elseif ($days == "14") {
            $sel3 = 'selected="selected"';
        } elseif ($days == "30") {
            $sel4 = 'selected="selected"';
        } elseif ($days == "60") {
            $sel5 = 'selected="selected"';
        } elseif ($days == "90") {
            $sel6 = 'selected="selected"';
        }
        
        echo '
                <div class="mb-3">
                    <select class="form-select" name="days">
                        <option ' . $sel1 . ' value="0">' . __d('search', 'Tous') . '</option>
                        <option ' . $sel2 . ' value="7">1 ' . __d('search', 'semaine') . '</option>
                        <option ' . $sel3 . ' value="14">2 ' . __d('search', 'semaines') . '</option>
                        <option ' . $sel4 . ' value="30">1 ' . __d('search', 'mois') . '</option>
                        <option ' . $sel5 . ' value="60">2 ' . __d('search', 'mois') . '</option>
                        <option ' . $sel6 . ' value="90">3 ' . __d('search', 'mois') . '</option>
                    </select>
                </div>';
        
        if (($type == 'stories') or ($type == '')) {
            $sel1 = 'checked="checked"';
        } elseif ($type == 'sections') {
            $sel3 = 'checked="checked"';
        } elseif ($type == 'users') {
            $sel4 = 'checked="checked"';
        } elseif ($type == 'reviews') {
            $sel5 = 'checked="checked"';
        } elseif ($type == 'archive') {
            $sel6 = 'checked="checked"';
        }

        echo '
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="sto" name="type" value="stories" ' . $sel1 . ' />
                        <label class="form-check-label" for="sto">' . __d('search', 'Articles') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="arc" name="type" value="archive" ' . $sel6 . ' />
                        <label class="form-check-label" for="arc">' . __d('search', 'Archives') . '</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="sec" name="type" value="sections" ' . $sel3 . ' />
                        <label class="form-check-label" for="sec">' . __d('search', 'Rubriques') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="use" name="type" value="users" ' . $sel4 . ' />
                        <label class="form-check-label" for="use">' . __d('search', 'Utilisateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="rev" name="type" value="reviews" ' . $sel5 . ' />
                        <label class="form-check-label" for="rev">' . __d('search', 'Critiques') . '</label>
                    </div>
                </div>
                <div class="mb-3">
                    <input class="btn btn-primary" type="submit" value="' . __d('search', 'Recherche') . '" />
                </div>
            </form>';
        
        if ($type == "stories" or $type == "archive" or !$type) {
            if ($category > 0) {
                $categ = "AND catid='$category' ";
            } elseif ($category == 0) {
                $categ = '';
            }
        
            if ($type == 'stories' or !$type) {
                $q = "SELECT s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome FROM stories s, authors a WHERE s.archive='0' AND s.aid=a.aid $categ";
            } else {
                $q = "SELECT s.sid, s.aid, s.title, s.time, a.url, s.topic, s.informant, s.ihome FROM stories s, authors a WHERE s.archive='1' AND s.aid=a.aid $categ";
            }

            if (isset($query)) {
                $q .= "AND (s.title LIKE '%$query_title%' OR s.hometext LIKE '%$query_body%' OR s.bodytext LIKE '%$query_body%' OR s.notes LIKE '%$query_body%') ";
            }

            // Membre OU Auteur
            if ($member != '') {
                $q .= "AND s.informant='$member' ";
            } else {
                if ($author != '') {
                    $q .= "AND s.aid='$author' ";
                }
            }
        
            if ($topic != '') {
                $q .= "AND s.topic='$topic' ";
            }
        
            if ($days != '' && $days != 0) {
                $q .= "AND TO_DAYS(NOW()) - TO_DAYS(time) <= '$days' ";
            }
        
            $q .= " ORDER BY s.time DESC" . $limit;
            $t = $topic;
        
            $x = 0;
        
            if ($SuperCache) {
                $cache_clef = "[objet]==> $q";
                $CACHE_TIMINGS[$cache_clef] = 3600;
        
                $cache_obj = new SuperCacheManager();
                $tab_sid = $cache_obj->startCachingObjet($cache_clef);
                
                if ($tab_sid != '') {
                    $x = count($tab_sid);
                }
            } else {
                $cache_obj = new SuperCacheEmpty();
            }
        
            if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
                $result = sql_query($q);
        
                if ($result) {
                    while (list($sid, $aid, $title, $time, $url, $topic, $informant, $ihome) = sql_fetch_row($result)) {
                        if (News::ctrl_aff($ihome, 0)) {
                            $tab_sid[$x]['sid'] = $sid;
                            $tab_sid[$x]['aid'] = $aid;
                            $tab_sid[$x]['title'] = $title;
                            $tab_sid[$x]['time'] = $time;
                            $tab_sid[$x]['url'] = $url;
                            $tab_sid[$x]['topic'] = $topic;
                            $tab_sid[$x]['informant'] = $informant;
                            $x++;
                        }
                    }
                }
            }
        
            if ($SuperCache) {
                $cache_obj->endCachingObjet($cache_clef, $tab_sid);
            }
        
            echo '
                <table id ="search_result" data-toggle="table" data-striped="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons">
                    <thead>
                        <tr>
                        <th data-sortable="true">' . __d('search', 'Résultats') . '</th>
                        </tr>
                    </thead>
                    <tbody>';
        
            if ($x < $offset) {
                $increment = $x;
            }
        
            if (($min + $offset) <= $x) {
                $increment = $offset;
            }
        
            if (($x - $min) < $offset) {
                $increment = ($x - $min);
            }
        
            for ($i = $min; $i < ($increment + $min); $i++) {
                $furl = 'article.php?sid=' . $tab_sid[$i]['sid'];
        
                if ($type == 'archive') {
                    $furl .= '&amp;archive=1';
                }
        
                Date::formatTimestamp($tab_sid[$i]['time']);
        
                echo '
                    <tr>
                       <td><span>[' . ($i + 1) . ']</span>&nbsp;' . __d('search', 'Contribution de') . ' <a href="user.php?op=userinfo&amp;uname=' . $tab_sid[$i]['informant'] . '">' . $tab_sid[$i]['informant'] . '</a> :<br /><strong><a href="' . $furl . '">' . Language::aff_langue($tab_sid[$i]['title']) . '</a></strong><br /><span>' . __d('search', 'Posté par ') . '<a href="' . $tab_sid[$i]['url'] . '" >' . $tab_sid[$i]['aid'] . '</a></span> ' . __d('search', 'le') . ' ' . $datetime . '</td>
                    </tr>';
            }
        
            echo '
                    </tbody>
                </table>';
        
            if ($x == 0) {
                echo '
                    <div class="alert alert-danger lead" role="alert">
                        <i class="fa fa-exclamation-triangle fa-lg me-2"></i>' . __d('search', 'Aucune correspondance à votre recherche n\'a été trouvée') . ' !
                    </div>';
            }
        
            $prev = ($min - $offset);
        
            echo '<br /><p align="left">(' . __d('search', 'Total') . ' : ' . $x . ')&nbsp;&nbsp;';
        
            if ($prev >= 0) {
                echo '<a href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $prev . '&amp;query=' . $query . '&amp;type=' . $type . '&amp;category=' . $category . '&amp;member=' . $member . '&amp;days=' . $days . '">';
                echo $offset . ' ' . __d('search', 'réponses précédentes') . '</a>';
            }
        
            if ($min + $increment < $x) {
                if ($prev >= 0) {
                    echo "&nbsp;|&nbsp;";
                }
        
                echo "<a href=\"search.php?author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type&amp;category=$category&amp;member=$member&amp;days=$days\">";
                echo __d('search', 'réponses suivantes') . "</a>";
            }
        
            echo '</p>';
        
            // reviews
        } elseif ($type == 'reviews') {
            $result = sql_query("SELECT id, title, text, reviewer FROM reviews WHERE (title LIKE '%$query_title%' OR text LIKE '%$query_body%') ORDER BY date DESC LIMIT $min,$offset");
            
            if ($result) {
                $nrows  = sql_num_rows($result);
            }
        
            $x = 0;
        
            if ($nrows > 0) {
                echo '
                <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
                    <thead>
                        <tr>
                        <th data-sortable="true">' . __d('search', 'Résultats') . '</th>
                        </tr>
                    </thead>
                    <tbody>';
        
                while (list($id, $title, $text, $reviewer) = sql_fetch_row($result)) {
                    $furl = "reviews.php?op=showcontent&amp;id=$id";
        
                    echo '
                    <tr>
                       <td><a href="' . $furl . '">' . $title . '</a> ' . __d('search', 'par') . ' <i class="fa fa-user text-muted"></i>&nbsp;' . $reviewer . '</td>
                    </tr>';
                    $x++;
                }
        
                echo '
                    </tbody>
                </table>';
        
            } else {
                echo '<div class="alert alert-danger lead">' . __d('search', 'Aucune correspondance à votre recherche n\'a été trouvée') . '</div>';
            }

            $prev = $min - $offset;
        
            echo '
                <p align="left">
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link" href="#">' . $nrows . '</a></li>';
        
            if ($prev >= 0) {
                echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $prev . '&amp;query=' . $query . '&amp;type=' . $type . '" >' . $offset . ' ' . __d('search', 'réponses précédentes') . '</a></li>';
            }
        
            if ($x >= ($offset - 1)) {
                echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $max . '&amp;query=' . $query . '&amp;type=' . $type . '" >' . __d('search', 'réponses suivantes') . '</a></li>';
            }
        
            echo '
                    </ul>
                </p>';
        
        // sections
        } elseif ($type == 'sections') {
            $t = '';
            $result = sql_query("SELECT artid, secid, title, content FROM seccont WHERE (title LIKE '%$query_title%' OR content LIKE '%$query_body%') ORDER BY artid DESC LIMIT $min,$offset");
            
            if ($result) {
                $nrows  = sql_num_rows($result);
            }
        
            $x = 0;
        
            if ($nrows > 0) {
                echo '
                <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
                    <thead>
                        <tr>
                        <th data-sortable="true">' . __d('search', 'Résultats') . '</th>
                        </tr>
                    </thead>
                    <tbody>';
        
                while (list($artid, $secid, $title, $content) = sql_fetch_row($result)) {
        
                    $rowQ2 = Q_Select("SELECT secname, rubid FROM sections WHERE secid='$secid'", 3600);
                    $row2 = $rowQ2[0];
        
                    $rowQ3 = Q_Select("SELECT rubname FROM rubriques WHERE rubid='" . $row2['rubid'] . "'", 3600);
                    $row3 = $rowQ3[0];
        
                    if ($row3['rubname'] != 'Divers' and $row3['rubname'] != 'Presse-papiers') {
                        $surl = "sections.php?op=listarticles&amp;secid=$secid";
                        $furl = "sections.php?op=viewarticle&amp;artid=$artid";
                        echo '
                        <tr>
                        <td><a href="' . $furl . '">' . Language::aff_langue($title) . '</a> ' . __d('search', 'dans la sous-rubrique') . ' <a href="' . $surl . '">' . Language::aff_langue($row2['secname']) . '</a></td>
                        </tr>';
                        $x++;
                    }
                }
        
                echo '
                    </tbody>
                </table>';
        
                if ($x == 0) {
                    echo '<div class="alert alert-danger lead">' . __d('search', 'Aucune correspondance à votre recherche n\'a été trouvée') . '</div>';
                }
            } else {
                echo '<div class="alert alert-danger lead">' . __d('search', 'Aucune correspondance à votre recherche n\'a été trouvée') . '</div>';
            }

            $prev = $min - $offset;
        
            echo '
                <p align="left">
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link" href="#">' . $nrows . '</a></li>';
        
            if ($prev >= 0) {
                echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $prev . '&amp;query=' . $query . '&amp;type=' . $type . '">' . $offset . ' ' . __d('search', 'réponses précédentes') . '</a></li>';
            }

            if ($x >= ($offset - 1)) {
                echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $max . '&amp;query=' . $query . '&amp;type=' . $type . '">' . __d('search', 'réponses suivantes') . '</a></li>';
            }

            echo '
                    </ul>
                </p>';
        
            // users
        } elseif ($type == 'users') {
            if ((Config::get('npds.member_list') and $user) or $admin) {
        
                $result = sql_query("SELECT uname, name FROM users WHERE (uname LIKE '%$query_title%' OR name LIKE '%$query_title%' OR bio LIKE '%$query_title%') ORDER BY uname ASC LIMIT $min,$offset");
                
                if ($result) {
                    $nrows  = sql_num_rows($result);
                }
        
                $x = 0;
        
                if ($nrows > 0) {
                    echo '
                    <table id ="search_result" data-toggle="table" data-striped="true" data-icons-prefix="fa" data-icons="icons">
                        <thead>
                            <tr>
                            <th data-sortable="true">' . __d('search', 'Résultats') . '</th>
                            </tr>
                        </thead>
                        <tbody>';
        
                    while (list($uname, $name) = sql_fetch_row($result)) {
                        $furl = "user.php?op=userinfo&amp;uname=$uname";
        
                        if ($name == '') {
                            $name = __d('search', 'Aucun nom n\'a été entré');
                        }
        
                        echo '
                        <tr>
                            <td><a href="' . $furl . '"><i class="fa fa-user text-muted me-2"></i>' . $uname . '</a> (' . $name . ')</td>
                        </tr>';
                        $x++;
                    }
        
                    echo '
                    <tbody>
                </table>';
        
                } else {
                    echo '<div class="alert alert-danger lead" role="alert">' . __d('search', 'Aucune correspondance à votre recherche n\'a été trouvée') . '</div>';
                }

                $prev = $min - $offset;
        
                echo '
                <p align="left">
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled"><a class="page-link" href="#">' . $nrows . '</a></li>';
        
                if ($prev >= 0) {
                    echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $prev . '&amp;query=' . $query . '&amp;type=' . $type . '">' . $offset . ' ' . __d('search', 'réponses précédentes') . '</a></li>';
                }

                if ($x >= ($offset - 1)) {
                    echo '<li class="page-item"><a class="page-link" href="search.php?author=' . $author . '&amp;topic=' . $t . '&amp;min=' . $max . '&amp;query=' . $query . '&amp;type=' . $type . '" >' . __d('search', 'réponses suivantes') . '</a></li>';
                }

                echo '
                    </ul>
                </p>';
            }
        }
    }

}
