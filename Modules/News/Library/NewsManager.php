<?php

namespace Modules\News\Library;


use App\Support\Facades\Theme;
use Modules\News\Contracts\NewsInterface;
use Modules\Npds\Support\Facades\Language;


class NewsManager implements NewsInterface 
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

    function code_aff($subject, $story, $bodytext, $notes)
    {
        global $local_user_language;
    
        $subjectX = aff_code(preview_local_langue($local_user_language, $subject));
        $storyX = aff_code(preview_local_langue($local_user_language, $story));
        $bodytextX = aff_code(preview_local_langue($local_user_language, $bodytext));
        $notesX = aff_code(preview_local_langue($local_user_language, $notes));
    
        themepreview($subjectX, $storyX, $bodytextX, $notesX);
    }
    
    function publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
    {
        $today = getdate(time() + ((int) Config::get('npds.gmt') * 3600));
    
        settype($dd_pub, 'string');
        settype($fd_pub, 'string');
        settype($dh_pub, 'string');
        settype($fh_pub, 'string');
    
        if (!$dd_pub) {
            $dd_pub .= $today['year'] . '-';
            
            if ($today['mon'] < 10) 
                $dd_pub .= '0' . $today['mon'] . '-';
            else 
                $dd_pub .= $today['mon'] . '-';
            
            if ($today['mday'] < 10) 
                $dd_pub .= '0' . $today['mday'];
            else 
                $dd_pub .= $today['mday'];
        }
    
        if (!$fd_pub) {
            $fd_pub .= ($today['year'] + 99) . '-';
            
            if ($today['mon'] < 10) 
                $fd_pub .= '0' . $today['mon'] . '-';
            else 
                $fd_pub .= $today['mon'] . '-';
            
            if ($today['mday'] < 10) 
                $fd_pub .= '0' . $today['mday'];
            else 
                $fd_pub .= $today['mday'];
        }
    
        if (!$dh_pub) {
            if ($today['hours'] < 10) 
                $dh_pub .= '0' . $today['hours'] . ':';
            else 
                $dh_pub .= $today['hours'] . ':';
            
            if ($today['minutes'] < 10) 
                $dh_pub .= '0' . $today['minutes'];
            else 
                $dh_pub .= $today['minutes'];
        }
    
        if (!$fh_pub) {
            if ($today['hours'] < 10) 
                $fh_pub .= '0' . $today['hours'] . ':';
            else 
                $fh_pub .= $today['hours'] . ':';
            
            if ($today['minutes'] < 10) 
                $fh_pub .= '0' . $today['minutes'];
            else 
                $fh_pub .= $today['minutes'];
        }
    
        echo '
        <hr />
        <p class="small text-end">
        ' . __d('news', date("l")) . date(" " . __d('news', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600)) . '
        </p>';
    
        if ($dd_pub != -1 and $dh_pub != -1)
            echo '
            <div class="row mb-3">
                <div class="col-sm-5 mb-2">
                    <label class="form-label" for="dd_pub">' . __d('news', 'Date de publication') . '</label>
                    <input type="text" class="form-control flatpi" id="dd_pub" name="dd_pub" value="' . $dd_pub . '" />
                </div>
                <div class="col-sm-3 mb-2">
                    <label class="form-label" for="dh_pub">' . __d('news', 'Heure') . '</label>
                    <div class="input-group clockpicker">
                        <span class="input-group-text"><i class="far fa-clock fa-lg"></i></span>
                        <input type="text" class="form-control" placeholder="Heure" id="dh_pub" name="dh_pub" value="' . $dh_pub . '" />
                    </div>
                </div>
            </div>';
    
        echo '
        <div class="row mb-3">
            <div class="col-sm-5 mb-2">
                <label class="form-label" for="fd_pub">' . __d('news', 'Date de fin de publication') . '</label>
                <input type="text" class="form-control flatpi" id="fd_pub" name="fd_pub" value="' . $fd_pub . '" />
            </div>
            <div class="col-sm-3 mb-2">
                <label class="form-label" for="fh_pub">' . __d('news', 'Heure') . '</label>
                <div class="input-group clockpicker">
                    <span class="input-group-text"><i class="far fa-clock fa-lg"></i></span>
                    <input type="text" class="form-control" placeholder="Heure" id="fh_pub" name="fh_pub" value="' . $fh_pub . '" />
                </div>
            </div>
        </div>
        <script type="text/javascript" src="assets/shared/flatpickr/dist/flatpickr.min.js"></script>
        <script type="text/javascript" src="assets/shared/flatpickr/dist/l10n/' . Language::language_iso(1, '', '') . '.js"></script>
        <script type="text/javascript" src="assets/shared/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>
        <script type="text/javascript">
        //<![CDATA[
            $(document).ready(function() {
                $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "assets/shared/flatpickr/dist/themes/App.css"});
                $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "assets/shared/bootstrap-clockpicker/bootstrap-clockpicker.min.css"});
                $(".clockpicker").clockpicker({
                    placement: "bottom",
                    align: "top",
                    autoclose: "true"
                });
    
            })
            const fp = flatpickr(".flatpi", {
                altInput: true,
                altFormat: "l j F Y",
                dateFormat:"Y-m-d",
                "locale": "' . language_iso(1, '', '') . '",
            });
        //]]>
        </script>
    
        <div class="mb-3 row">
            <label class="col-form-label">' . __d('news', 'Epuration de la new à la fin de sa date de validité') . '</label>';
    
        $sel1 = '';
        $sel2 = '';
    
        if (!$epur) 
            $sel2 = 'checked="checked"';
        else 
            $sel1 = 'checked="checked"';
    
        echo '
            <div class="col-sm-8 my-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="epur_y" name="epur" value="1" ' . $sel1 . ' />
                    <label class="form-check-label" for="epur_y">' . __d('news', 'Oui') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="epur_n" name="epur" value="0" ' . $sel2 . ' />
                    <label class="form-check-label" for="epur_n">' . __d('news', 'Non') . '</label>
                </div>
            </div>
        </div>
        <hr />';
    }

    /**
     * [ultramode description]
     *
     * @return  [type]  [return description]
     */
    public function ultramode()
    {
        

        $ultra = "storage/cache/ultramode.txt";
        $netTOzone = "storage/cache/net2zone.txt";

        $file = fopen("$ultra", "w");
        $file2 = fopen("$netTOzone", "w");

        fwrite($file, "General purpose self-explanatory file with news headlines\n");

        $storynum = Config::get('npds.storyhome');

        $xtab = $this->news_aff('index', "WHERE ihome='0' AND archive='0'", Config::get('npds.storyhome'), '');

        $story_limit = 0;
        while (($story_limit < $storynum) and ($story_limit < sizeof($xtab))) {
            list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
            
            $story_limit++;

            $rfile2 = sql_query("SELECT topictext, topicimage 
                                FROM topics 
                                WHERE topicid='$topic'");

            list($topictext, $topicimage) = sql_fetch_row($rfile2);

            $hometext = meta_lang(strip_tags($hometext));

            fwrite($file, "%%\n$title\nConfig::get('npds.nuke_url')/article.php?sid=$sid\n$time\n$aid\n$topictext\n$hometext\n$topicimage\n");
            fwrite($file2, "<NEWS>\n<NBX>$topictext</NBX>\n<TITLE>" . stripslashes($title) . "</TITLE>\n<SUMMARY>$hometext</SUMMARY>\n<URL>Config::get('npds.nuke_url')/article.php?sid=$sid</URL>\n<AUTHOR>" . $aid . "</AUTHOR>\n</NEWS>\n\n");
        }

        fclose($file);
        fclose($file2);
    }

    /**
     * [automatednews description]
     *
     * @return  [type]  [return description]
     */
    public function automatednews()
    {
        $today = getdate(time() + ((int) Config::get('npds.gmt') * 3600));
        $day = $today['mday'];
    
        if ($day < 10) {
            $day = "0$day";
        }
    
        $month = $today['mon'];
    
        if ($month < 10) {
            $month = "0$month";
        }
    
        $year = $today['year'];
        $hour = $today['hours'];
        $min = $today['minutes'];
    
        $result = sql_query("SELECT anid, date_debval FROM autonews WHERE date_debval LIKE '$year-$month%'");
    
        while (list($anid, $date_debval) = sql_fetch_row($result)) {
            preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $date_debval, $date);
    
            if (($date[1] <= $year) and ($date[2] <= $month) and ($date[3] <= $day)) {
                if (($date[4] < $hour) and ($date[5] >= $min) or ($date[4] <= $hour) and ($date[5] <= $min) or (($day - $date[3]) >= 1)) {
                    
                    $result2 = sql_query("SELECT catid, aid, title, hometext, bodytext, topic, informant, notes, ihome, date_finval, auto_epur FROM autonews WHERE anid='$anid'");
                    while (list($catid, $aid, $title, $hometext, $bodytext, $topic, $author, $notes, $ihome, $date_finval, $epur) = sql_fetch_row($result2)) {
    
                        $subject = stripslashes(FixQuotes($title));
                        $hometext = stripslashes(FixQuotes($hometext));
                        $bodytext = stripslashes(FixQuotes($bodytext));
                        $notes = stripslashes(FixQuotes($notes));
    
                        sql_query("INSERT INTO stories VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$author', '$notes', '$ihome', '0', '$date_finval', '$epur')");
                        sql_query("DELETE FROM autonews WHERE anid='$anid'");
                        
                        if (Config::get('npds.subscribe')) {
                            subscribe_mail('topic', $topic, '', $subject, '');
                        }
    
                        // Réseaux sociaux
                        if (file_exists('modules/App_twi/App_to_twi.php')) {
                            include('modules/App_twi/App_to_twi.php');
                        }
    
                        if (file_exists('modules/App_fbk/App_to_fbk.php')) {
                            include('modules/App_twi/App_to_fbk.php');
                        }
                        // Réseaux sociaux
                    }
                }
            }
        }
    
        // Purge automatique
        $result = sql_query("SELECT sid, date_finval, auto_epur FROM stories WHERE date_finval LIKE '$year-$month%'");
    
        while (list($sid, $date_finval, $epur) = sql_fetch_row($result)) {
    
            preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $date_finval, $date);
    
            if (($date[1] <= $year) and ($date[2] <= $month) and ($date[3] <= $day)) {
                if (($date[4] < $hour) and ($date[5] >= $min) or ($date[4] <= $hour) and ($date[5] <= $min)) {
                    
                    if ($epur == 1) {
                        sql_query("DELETE FROM stories WHERE sid='$sid'");
    
                        if (file_exists("modules/comments/article.conf.php")) {
                            include("modules/comments/article.conf.php");
                            
                            sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$topic'");
                        }
    
                        Ecr_Log('security', "removeStory ($sid, epur) by automated epur : system", '');
                    } else {
                        sql_query("UPDATE stories SET archive='1' WHERE sid='$sid'");
                    }
                }
            }
        }
    }

    /**
     * [ctrl_aff description]
     *
     * @param   [type]  $ihome  [$ihome description]
     * @param   [type]  $catid  [$catid description]
     *
     * @return  [type]          [return description]
     */
    public function ctrl_aff($ihome, $catid = 0)
    {
        global $user;

        $affich = false;

        if ($ihome == -1 and (!$user)) {
            $affich = true;
        } elseif ($ihome == 0) {
            $affich = true;
        } elseif ($ihome == 1) {
            $affich = $catid > 0 ? false : true;
        } elseif (($ihome > 1) and ($ihome <= 127)) {
            $tab_groupe = valid_group($user);
            
            if ($tab_groupe) {
                foreach ($tab_groupe as $groupevalue) {
                    if ($groupevalue == $ihome) {
                        $affich = true;
                        break;
                    }
                }
            }
        } else {
            if ($user) {
                $affich = true;
            }
        }

        return $affich;
    }

    /**
     * [aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    public function aff_news($op, $catid, $marqeur)
    {
        $url = $op;
    
        if ($op == 'edito-newindex') {
            if ($marqeur == 0) aff_edito();
            $op = 'news';
        }
    
        if ($op == "newindex") {
            $op = $catid == '' ? 'news' : 'categories';
        }
    
        if ($op == 'newtopic') {
            $op = 'topics';
        }
    
        if ($op == 'newcategory') {
            $op = 'categories';
        }
    
        $news_tab = prepa_aff_news($op, $catid, $marqeur);
        $story_limit = 0;
    
        // si le tableau $news_tab est vide alors return 
        if (is_null($news_tab)) {
            return;
        }
    
        $newscount = sizeof($news_tab);
    
        while ($story_limit < $newscount) {
            $story_limit++;
            $aid = unserialize($news_tab[$story_limit]['aid']);
            $informant = unserialize($news_tab[$story_limit]['informant']);
            $datetime = unserialize($news_tab[$story_limit]['datetime']);
            $title = unserialize($news_tab[$story_limit]['title']);
            $counter = unserialize($news_tab[$story_limit]['counter']);
            $topic = unserialize($news_tab[$story_limit]['topic']);
            $hometext = unserialize($news_tab[$story_limit]['hometext']);
            $notes = unserialize($news_tab[$story_limit]['notes']);
            $morelink = unserialize($news_tab[$story_limit]['morelink']);
            $topicname = unserialize($news_tab[$story_limit]['topicname']);
            $topicimage = unserialize($news_tab[$story_limit]['topicimage']);
            $topictext = unserialize($news_tab[$story_limit]['topictext']);
            $s_id = unserialize($news_tab[$story_limit]['id']);
    
            Theme::themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext, $s_id);
        }
    
        $transl1 = __d('news', 'Page suivante');
        $transl2 = __d('news', 'Home');
    
        global $cookie;
    
        $storynum = isset($cookie[3]) ? $cookie[3] : Config::get('npds.storyhome');
    
        if ($op == 'categories') {
            if (sizeof($news_tab) == $storynum) {
                $marqeur = $marqeur + sizeof($news_tab);
    
                echo '<div class="text-end"><a href="index.php?op=' . $url . '&amp;catid=' . $catid . '&amp;marqeur=' . $marqeur . '" class="page_suivante" >' . $transl1 . '<i class="fa fa-chevron-right fa-lg ms-2" title="' . $transl1 . '" data-bs-toggle="tooltip"></i></a></div>';
            } else {
                if ($marqeur >= $storynum) {
                    echo '<div class="text-end"><a href="index.php?op=' . $url . '&amp;catid=' . $catid . '&amp;marqeur=0" class="page_suivante" title="' . $transl2 . '">' . $transl2 . '</a></div>';
                }
            }
        }
    
        if ($op == 'news') {
            if (sizeof($news_tab) == $storynum) {
                $marqeur = $marqeur + sizeof($news_tab);
    
                echo '<div class="text-end"><a href="index.php?op=' . $url . '&amp;catid=' . $catid . '&amp;marqeur=' . $marqeur . '" class="page_suivante" >' . $transl1 . '<i class="fa fa-chevron-right fa-lg ms-2" title="' . $transl1 . '" data-bs-toggle="tooltip"></i></a></div>';
            } else {
                if ($marqeur >= $storynum) {
                    echo '<div class="text-end"><a href="index.php?op=' . $url . '&amp;catid=' . $catid . '&amp;marqeur=0" class="page_suivante" title="' . $transl2 . '">' . $transl2 . '</a></div>';
                }
            }
        }
    
        if ($op == 'topics') {
            if (sizeof($news_tab) == $storynum) {
                $marqeur = $marqeur + sizeof($news_tab);
    
                echo '<div align="right"><a href="index.php?op=newtopic&amp;topic=' . $topic . '&amp;marqeur=' . $marqeur . '" class="page_suivante" >' . $transl1 . '<i class="fa fa-chevron-right fa-lg ms-2" title="' . $transl1 . '" data-bs-toggle="tooltip"></i></a></div>';
            } else {
                if ($marqeur >= $storynum) {
                    echo '<div class="text-end"><a href="index.php?op=newtopic&amp;topic=' . $topic . '&amp;marqeur=0" class="page_suivante" title="' . $transl2 . '">' . $transl2 . '</a></div>';
                }
            }
        }
    }

    /**
     * [news_aff description]
     *
     * @param   [type]  $type_req  [$type_req description]
     * @param   [type]  $sel       [$sel description]
     * @param   [type]  $storynum  [$storynum description]
     * @param   [type]  $oldnum    [$oldnum description]
     *
     * @return  [type]             [return description]
     */    
    public function news_aff($type_req, $sel, $storynum, $oldnum)
    { 
        // pas stabilisé ...!
        

        // Astuce pour afficher le nb de News correct même si certaines News ne sont pas visibles (membres, groupe de membres)
        // En fait on * le Nb de News par le Nb de groupes
        $row_Q2 = Q_select("SELECT COUNT(groupe_id) AS total FROM groupes", 86400);

        $NumG = $row_Q2[0];

        if ($NumG['total'] < 2) {
            $coef = 2;
        } else {
            $coef = $NumG['total'];
        }

        settype($storynum, "integer");

        if ($type_req == 'index') {
            $Xstorynum = $storynum * $coef;
            $result = Q_select("SELECT sid, catid, ihome FROM stories $sel ORDER BY sid DESC LIMIT $Xstorynum", 3600);
            $Znum = $storynum;
        }

        if ($type_req == 'old_news') {
            //      $Xstorynum=$oldnum*$coef;
            $result = Q_select("SELECT sid, catid, ihome, time FROM stories $sel ORDER BY time DESC LIMIT $storynum", 3600);
            $Znum = $oldnum;
        }

        if (($type_req == 'big_story') or ($type_req == 'big_topic')) {
            //      $Xstorynum=$oldnum*$coef;
            $result = Q_select("SELECT sid, catid, ihome, counter FROM stories $sel ORDER BY counter DESC LIMIT $storynum", 0);
            $Znum = $oldnum;
        }

        if ($type_req == 'libre') {
            $Xstorynum = $oldnum * $coef; //need for what ?
            $result = Q_select("SELECT sid, catid, ihome, time FROM stories $sel", 3600);
            $Znum = $oldnum;
        }

        if ($type_req == 'archive') {
            $Xstorynum = $oldnum * $coef; //need for what ?
            $result = Q_select("SELECT sid, catid, ihome FROM stories $sel", 3600);
            $Znum = $oldnum;
        }

        $ibid = 0;

        settype($tab, 'array');

        foreach ($result as $myrow) {
            $s_sid = $myrow['sid'];
            $catid = $myrow['catid'];
            $ihome = $myrow['ihome'];

            if (array_key_exists('time', $myrow)) {
                $time = $myrow['time'];
            }

            if ($ibid == $Znum) {
                break;
            }

            if ($type_req == "libre") {
                $catid = 0;
            }

            if ($type_req == "archive") {
                $ihome = 0;
            }

            if ($this->ctrl_aff($ihome, $catid)) {
                if (($type_req == "index") or ($type_req == "libre")) {
                    $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM stories WHERE sid='$s_sid' AND archive='0'");
                }

                if ($type_req == "archive") {
                    $result2 = sql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM stories WHERE sid='$s_sid' AND archive='1'");
                }

                if ($type_req == "old_news") {
                    $result2 = sql_query("SELECT sid, title, time, comments, counter FROM stories WHERE sid='$s_sid' AND archive='0'");
                }

                if (($type_req == "big_story") or ($type_req == "big_topic")) {
                    $result2 = sql_query("SELECT sid, title FROM stories WHERE sid='$s_sid' AND archive='0'");
                }

                $tab[$ibid] = sql_fetch_row($result2);

                if (is_array($tab[$ibid])) {
                    $ibid++;
                }

                sql_free_result($result2);
            }
        }

        @sql_free_result($result);

        return $tab;
    }

    /**
     * [prepa_aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    public function prepa_aff_news($op, $catid, $marqeur)
    {
        global $topicname, $topicimage, $topictext, $datetime, $cookie;

        if (isset($cookie[3])) {
            $storynum = $cookie[3];
        } else {
            $storynum = $storyhome;
        }

        if ($op == "categories") {
            sql_query("UPDATE stories_cat SET counter=counter+1 WHERE catid='$catid'");

            settype($marqeur, "integer");

            if (!isset($marqeur)) {
                $marqeur = 0;
            }

            $xtab = $this->news_aff("libre", "WHERE catid='$catid' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum", "", "-1");

            $storynum = sizeof($xtab);
        } elseif ($op == "topics") {

            settype($marqeur, "integer");

            if (!isset($marqeur)) {
                $marqeur = 0;
            }

            $xtab = $this->news_aff("libre", "WHERE topic='$catid' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum", "", "-1");

            $storynum = sizeof($xtab);
        } elseif ($op == "news") {

            settype($marqeur, "integer");

            if (!isset($marqeur)) {
                $marqeur = 0;
            }

            $xtab = $this->news_aff("libre", "WHERE ihome!='1' AND archive='0' ORDER BY sid DESC LIMIT $marqeur,$storynum", "", "-1");

            $storynum = sizeof($xtab);
        } elseif ($op == "article") {
            $xtab = $this->news_aff("index", "WHERE ihome!='1' AND sid='$catid'", 1, "");
        } else {
            $xtab = $this->news_aff("index", "WHERE ihome!='1' AND archive='0'", $storynum, "");
        }

        $story_limit = 0;
        while (($story_limit < $storynum) and ($story_limit < sizeof($xtab))) {
            list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[$story_limit];
            
            $story_limit++;
            
            $printP = '<a href="print.php?sid=' . $s_sid . '" class="me-3" title="' . __d('news', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-print"></i></a>&nbsp;';
            $sendF = '<a href="friend.php?op=FriendSend&amp;sid=' . $s_sid . '" class="me-3" title="' . __d('news', 'Envoyer cet article à un ami') . '" data-bs-toggle="tooltip" ><i class="fa fa-lg fa-at"></i></a>';
            
            $this->getTopics($s_sid);
            
            $title = aff_langue(stripslashes($title));
            $hometext = aff_langue(stripslashes($hometext));
            $notes = aff_langue(stripslashes($notes));
            $bodycount = strlen(strip_tags(aff_langue($bodytext), '<img>'));

            if ($bodycount > 0) {
                $bodycount = strlen(strip_tags(aff_langue($bodytext)));

                if ($bodycount > 0) {
                    $morelink[0] = wrh($bodycount) . ' ' . __d('news', 'caractères de plus');
                } else {
                    $morelink[0] = ' ';
                }

                $morelink[1] = ' <a href="article.php?sid=' . $s_sid . '" >' . __d('news', 'Lire la suite...') . '</a>';
            } else {
                $morelink[0] = '';
                $morelink[1] = '';
            }

            if ($comments == 0) {
                $morelink[2] = 0;
                $morelink[3] = '<a href="article.php?sid=' . $s_sid . '" class="me-3"><i class="far fa-comment fa-lg" title="' . __d('news', 'Commentaires ?') . '" data-bs-toggle="tooltip"></i></a>';
            } elseif ($comments == 1) {
                $morelink[2] = $comments;
                $morelink[3] = '<a href="article.php?sid=' . $s_sid . '" class="me-3"><i class="far fa-comment fa-lg" title="' . __d('news', 'Commentaire') . '" data-bs-toggle="tooltip"></i></a>';
            } else {
                $morelink[2] = $comments;
                $morelink[3] = '<a href="article.php?sid=' . $s_sid . '" class="me-3" ><i class="far fa-comment fa-lg" title="' . __d('news', 'Commentaires') . '" data-bs-toggle="tooltip"></i></a>';
            }

            $morelink[4] = $printP;
            $morelink[5] = $sendF;
            $sid = $s_sid;

            if ($catid != 0) {
                $resultm = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
                list($title1) = sql_fetch_row($resultm);

                $title = $title;
                // Attention à cela aussi
                $morelink[6] = ' <a href="index.php?op=newcategory&amp;catid=' . $catid . '">&#x200b;' . aff_langue($title1) . '</a>';
            } else {
                $morelink[6] = '';
            }

            $news_tab[$story_limit]['aid'] = serialize($aid);
            $news_tab[$story_limit]['informant'] = serialize($informant);
            $news_tab[$story_limit]['datetime'] = serialize($time);
            $news_tab[$story_limit]['title'] = serialize($title);
            $news_tab[$story_limit]['counter'] = serialize($counter);
            $news_tab[$story_limit]['topic'] = serialize($topic);
            $news_tab[$story_limit]['hometext'] = serialize(meta_lang(aff_code($hometext)));
            $news_tab[$story_limit]['notes'] = serialize(meta_lang(aff_code($notes)));
            $news_tab[$story_limit]['morelink'] = serialize($morelink);
            $news_tab[$story_limit]['topicname'] = serialize($topicname);
            $news_tab[$story_limit]['topicimage'] = serialize($topicimage);
            $news_tab[$story_limit]['topictext'] = serialize($topictext);
            $news_tab[$story_limit]['id'] = serialize($s_sid);
        }

        if (isset($news_tab)) {
            return $news_tab;
        }
    }

    /**
     * [getTopics description]
     *
     * @param   [type]  $s_sid  [$s_sid description]
     *
     * @return  [type]          [return description]
     */
    public function getTopics($s_sid)
    {
        global $topicname, $topicimage, $topictext;

        $sid = $s_sid;
        $result = sql_query("SELECT topic FROM stories WHERE sid='$sid'");

        if ($result) {
            list($topic) = sql_fetch_row($result);
            $result = sql_query("SELECT topicid, topicname, topicimage, topictext FROM topics WHERE topicid='$topic'");

            if ($result) {
                list($topicid, $topicname, $topicimage, $topictext) = sql_fetch_row($result);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
