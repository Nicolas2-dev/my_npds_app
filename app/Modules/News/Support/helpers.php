<?php

use App\Modules\News\Library\NewsManager;
use App\Modules\News\Support\Facades\News;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\NewsLibrary\Compress\GzFile;
use App\Modules\Groupes\Support\Facades\Groupe;
use App\Modules\News\Library\News\Compress\ZipFile;


// a transferer dans library ici provisoirement

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @return void
     */
    function user_articles($uname)
    {
        $xtab = News::news_aff("libre", "WHERE informant='$uname' ORDER BY sid DESC LIMIT 10", '', 10);

        if (!empty($xtab)) {
            $userinfo = '
            <h4 class="my-3">' . __d('users', 'Les derniers articles de') . ' ' . $uname . '.</h4>
            <div id="last_article_by" class="card card-body mb-3">';

            $story_limit = 0;
        
            while (($story_limit < 10) and ($story_limit < sizeof($xtab))) {
                list($sid, $catid, $aid, $title, $time) = $xtab[$story_limit];
        
                $story_limit++;
        
                $userinfo .= '
                <div class="d-flex">
                    <div class="p-2"><a href="article.php?sid=' . $sid . '">' . aff_langue($title) . '</a></div>
                    <div class="ms-auto p-2">' . $time . '</div>
                </div>';
            }
        
            return $userinfo .= '
            </div>';
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @param [type] $uid
     * @return void
     */
    function user_contributions($uname, $uid)
    {
        $result = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND poster_id=$uid ORDER BY post_time DESC LIMIT 0,50");            
          
        if (!empty($result)) {

            $userinfo = '
            <h4 class="my-3">' . __d('users', 'Les dernières contributions de') . ' ' . $uname . '</h4>';
            
            $nbp = 10;
            $content = '';

            $j = 1;
     
            while (list($post_id, $post_text) = sql_fetch_row($result) and $j <= $nbp) {
                // Requete detail dernier post
                $res = sql_query("SELECT 
                    us.topic_id, us.forum_id, us.poster_id, us.post_time, 
                    uv.topic_title, 
                    ug.forum_name, ug.forum_type, ug.forum_pass, 
                    ut.uname 
                FROM 
                    posts us, 
                    forumtopics uv, 
                    forums ug, 
                    users ut 
                WHERE 
                    us.post_id = $post_id 
                    AND uv.topic_id = us.topic_id 
                    AND uv.forum_id = ug.forum_id 
                    AND ut.uid = us.poster_id LIMIT 1");
        
                list($topic_id, $forum_id, $poster_id, $post_time, $topic_title, $forum_name, $forum_type, $forum_pass, $uname) = sql_fetch_row($res);
        
                if (($forum_type == '5') or ($forum_type == '7')) {
                    $ok_affich = false;
                    $tab_groupe = Groupe::valid_group(Auth::check('user'));
                    $ok_affich = Groupe::groupe_forum($forum_pass, $tab_groupe);
                } else {
                    $ok_affich = true;
                }

                if ($ok_affich) {
                    // Nbre de postes par sujet
                    $TableRep = sql_query("SELECT * FROM posts WHERE forum_id > 0 AND topic_id = '$topic_id'");
        
                    $replys = sql_num_rows($TableRep) - 1;
                    $id_lecteur = isset($cookie[0]) ? $cookie[0] : '0';
        
                    $sqlR = "SELECT rid FROM forum_read WHERE topicid = '$topic_id' AND uid = '$id_lecteur' AND status != '0'";
        
                    if (sql_num_rows(sql_query($sqlR)) == 0) {
                        $image = '<a href="" title="' . __d('users', 'Non lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg faa-shake animated text-primary "></i></a>';
                    } else {
                        $image = '<a title="' . __d('users', 'Lu') . '" data-bs-toggle="tooltip"><i class="far fa-file-alt fa-lg text-primary"></i></a>';
                    }

                    $content .= '
                    <p class="mb-0 list-group-item list-group-item-action flex-column align-items-start" >
                        <span class="d-flex w-100 mt-1">
                        <span>' . $post_time . '</span>
                        <span class="ms-auto">
                        <span class="badge bg-secondary ms-1" title="' . __d('users', 'Réponses') . '" data-bs-toggle="tooltip" data-bs-placement="left">' . $replys . '</span>
                        </span>
                    </span>
                    <span class="d-flex w-100"><br /><a href="viewtopic.php?topic=' . $topic_id . '&forum=' . $forum_id . '" data-bs-toggle="tooltip" title="' . $forum_name . '">' . $topic_title . '</a><span class="ms-auto mt-1">' . $image . '</span></span>
                    </p>';
        
                    $j++;
                }
            }
        
            $userinfo .= $content;
            return $userinfo .= '<hr />';
        }
    }



if (! function_exists('ultramode'))
{
    /**
     * [ultramode description]
     *
     * @return  [type]  [return description]
     */
    function ultramode()
    {
        return NewsManager::getInstance()->ultramode();
    }
}

if (! function_exists('automatednews'))
{
    /**
     * [automatednews description]
     *
     * @return  [type]  [return description]
     */
    function automatednews()
    {
        return NewsManager::getInstance()->automatednews();
    }
}

if (! function_exists('ctrl_aff'))
{
    /**
     * [ctrl_aff description]
     *
     * @param   [type]  $ihome  [$ihome description]
     * @param   [type]  $catid  [$catid description]
     *
     * @return  [type]          [return description]
     */
    function ctrl_aff($ihome, $catid = 0)
    {
        return NewsManager::getInstance()->ctrl_aff($ihome, $catid);
    }
}

if (! function_exists('aff_news'))
{
    /**
     * [aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    function aff_news($op, $catid, $marqeur)
    {
        return NewsManager::getInstance()->aff_news($op, $catid, $marqeur);
    }
}

if (! function_exists('news_aff'))
{
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
    function news_aff($type_req, $sel, $storynum, $oldnum)
    {
        return NewsManager::getInstance()->news_aff($type_req, $sel, $storynum, $oldnum);
    }
}

if (! function_exists('prepa_aff_news'))
{
    /**
     * [prepa_aff_news description]
     *
     * @param   [type]  $op       [$op description]
     * @param   [type]  $catid    [$catid description]
     * @param   [type]  $marqeur  [$marqeur description]
     *
     * @return  [type]            [return description]
     */
    function prepa_aff_news($op, $catid, $marqeur)
    {
        return NewsManager::getInstance()->prepa_aff_news($op, $catid, $marqeur);
    }
}

if (! function_exists('getTopics'))
{
    /**
     * [getTopics description]
     *
     * @param   [type]  $s_sid  [$s_sid description]
     *
     * @return  [type]          [return description]
     */
    function getTopics($s_sid)
    {
        return NewsManager::getInstance()->getTopics($s_sid);
    }
}

if (! function_exists('send_file'))
{    
    /**
     * [send_file description]
     *
     * @param   [type]  $line       [$line description]
     * @param   [type]  $filename   [$filename description]
     * @param   [type]  $extension  [$extension description]
     * @param   [type]  $MSos       [$MSos description]
     *
     * @return  [type]              [return description]
     */
    function send_file($line, $filename, $extension, $MSos)
    {
        $compressed = false;

        if (file_exists("Library/News/Archive.php")) {
            if (function_exists("gzcompress")) {
                $compressed = true;
            }
        }

        if ($compressed) {
            if ($MSos) {
                $arc = new ZipFile();
                $filez = $filename . ".zip";
            } else {
                $arc = new GzFile();
                $filez = $filename . ".gz";
            }

            $arc->addfile($line, $filename . "." . $extension, "");
            $arc->arc_getdata();
            $arc->filedownload($filez);

        } else {
            if ($MSos) {
                header("Content-Type: application/octetstream");
            } else {
                header("Content-Type: application/octet-stream");
            }

            header("Content-Disposition: attachment; filename=\"$filename." . "$extension\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo $line;
        }
    }
}

if (! function_exists('send_tofile'))
{    
    /**
     * [send_tofile description]
     *
     * @param   [type]  $line        [$line description]
     * @param   [type]  $repertoire  [$repertoire description]
     * @param   [type]  $filename    [$filename description]
     * @param   [type]  $extension   [$extension description]
     * @param   [type]  $MSos        [$MSos description]
     *
     * @return  [type]               [return description]
     */
    function send_tofile($line, $repertoire, $filename, $extension, $MSos)
    {
        $compressed = false;

        if (file_exists("Library/News/Archive.php")) {
            if (function_exists("gzcompress")) {
                $compressed = true;
            }
        }

        if ($compressed) {
            if ($MSos) {
                $arc = new ZipFile();
                $filez = $filename . ".zip";
            } else {
                $arc = new GzFile();
                $filez = $filename . ".gz";
            }

            $arc->addfile($line, $filename . "." . $extension, "");
            $arc->arc_getdata();

            if (file_exists($repertoire . "/" . $filez)) 
                unlink($repertoire . "/" . $filez);

            $arc->filewrite($repertoire . "/" . $filez, $perms = null);
        } else {
            if ($MSos) {
                header("Content-Type: application/octetstream");
            } else {
                header("Content-Type: application/octet-stream");
            }

            header("Content-Disposition: attachment; filename=\"$filename." . "$extension\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            echo $line;
        }
    }
}
