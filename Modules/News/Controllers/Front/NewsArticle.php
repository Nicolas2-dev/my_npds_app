<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class NewsArticle extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        settype($sid, "integer");
        settype($archive, "integer");
        
        if (!isset($sid) && !isset($tid))
            header("Location: index.php");
        
        if (!isset($archive))
            $archive = 0;
        
        $xtab = (!$archive)
            ? news_aff("libre", "WHERE sid='$sid'", 1, 1)
            : news_aff("archive", "WHERE sid='$sid'", 1, 1);
        
        list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
        
        if (!$aid)
            header("Location: index.php");
        
        sql_query("UPDATE stories SET counter=counter+1 WHERE sid='$sid'");
        
        include("header.php");
        
        // Include cache manager
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new cacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
            $title = aff_langue(stripslashes($title));
            $hometext = aff_code(aff_langue(stripslashes($hometext)));
            $bodytext = aff_code(aff_langue(stripslashes($bodytext)));
            $notes = aff_code(aff_langue(stripslashes($notes)));
        
            if ($notes != '')
                $notes = '<div class="note blockquote">' . __d('news', 'Note') . ' : ' . $notes . '</div>';
        
            $bodytext = $bodytext == ''
                ? meta_lang($hometext . '<br />' . $notes)
                : meta_lang($hometext . '<br />' . $bodytext . '<br />' . $notes);
        
            if ($informant == '')
                $informant = Config::get('npds.anonymous');
        
            getTopics($sid);
        
            if ($catid != 0) {
                $resultx = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
                list($title1) = sql_fetch_row($resultx);
        
                $title = '<a href="index.php?op=newindex&amp;catid=' . $catid . '"><span>' . aff_langue($title1) . '</span></a> : ' . $title;
            }
        
            $boxtitle = __d('news', 'Liens relatifs');
            $boxstuff = '<ul>';
        
            $result = sql_query("SELECT name, url FROM related WHERE tid='$topic'");
            while (list($name, $url) = sql_fetch_row($result)) {
                $boxstuff .= '
                 <li><a href="' . $url . '" target="_blank"><span>' . $name . '</span></a></li>';
            }
        
            $boxstuff .= '
              </ul>
              <ul>
                 <li><a href="search.php?topic=' . $topic . '" >' . __d('news', 'En savoir plus à propos de') . ' : </a><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . aff_langue($topicname) . '</span></span></li>
                 <li><a href="search.php?member=' . $informant . '" >' . __d('news', 'Article de') . ' ' . $informant . '</a> ' . userpopover($informant, 36, '') . '</li>
              </ul>
              <div><span class="fw-semibold">' . __d('news', 'L\'article le plus lu à propos de') . ' : </span><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . aff_langue($topicname) . '</span></span></div>';
        
            $xtab = news_aff("big_story", "WHERE topic=$topic", 1, 1);
        
            list($topstory, $ttitle) = $xtab[0];
        
            $boxstuff .= '
              <ul>
                 <li><a href="article.php?sid=' . $topstory . '" >' . aff_langue($ttitle) . '</a></li>
              </ul>
              <div><span class="fw-semibold">' . __d('news', 'Les dernières nouvelles à propos de') . ' : </span><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . aff_langue($topicname) . '</span></span></div>';
        
            $xtab = (!$archive)
                ? news_aff("libre", "WHERE topic=$topic AND archive='0' ORDER BY sid DESC LIMIT 0,5", 0, 5)
                : news_aff("archive", "WHERE topic=$topic AND archive='1' ORDER BY sid DESC LIMIT 0,5", 0, 5);
        
            $story_limit = 0;
            $boxstuff .= '<ul>';
        
            while (($story_limit < 5) and ($story_limit < sizeof($xtab))) {
                list($sid1, $catid1, $aid1, $title1) = $xtab[$story_limit];
        
                $story_limit++;
                $title1 = aff_langue(addslashes($title1));
        
                $boxstuff .= '
                 <li><a href="article.php?sid=' . $sid1 . '&amp;archive=' . $archive . '" >' . aff_langue(stripslashes($title1)) . '</a></li>';
            }
        
            $boxstuff .= '
              </ul>
              <p align="center">
                 <a href="print.php?sid=' . $sid . '&amp;archive=' . $archive . '" ><i class="fa fa-print fa-2x me-3" title="' . __d('news', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip"></i></a>
                 <a href="friend.php?op=FriendSend&amp;sid=' . $sid . '&amp;archive=' . $archive . '"><i class="fa fa-2x fa-at" title="' . __d('news', 'Envoyer cet article à un ami') . '" data-bs-toggle="tooltip"></i></a>
              </p>';
        
            if (!$archive) {
                $previous_tab = news_aff("libre", "WHERE sid<'$sid' ORDER BY sid DESC ", 0, 1);
                $next_tab = news_aff("libre", "WHERE sid>'$sid' ORDER BY sid ASC ", 0, 1);
            } else {
                $previous_tab = news_aff("archive", "WHERE sid<'$sid' ORDER BY sid DESC", 0, 1);
                $next_tab = news_aff("archive", "WHERE sid>'$sid' ORDER BY sid ASC ", 0, 1);
            }
        
            if (array_key_exists(0, $previous_tab))
                list($previous_sid) = $previous_tab[0];
            else
                $previous_sid = 0;
        
            if (array_key_exists(0, $next_tab))
                list($next_sid) = $next_tab[0];
            else
                $next_sid = 0;
        
            themearticle($aid, $informant, $time, $title, $bodytext, $topic, $topicname, $topicimage, $topictext, $sid, $previous_sid, $next_sid, $archive);
        
            // theme sans le système de commentaire en meta-mot !
            if (!function_exists("Caff_pub")) {
                if (file_exists("modules/comments/article.conf.php")) {
                    include("modules/comments/article.conf.php");
                    include("modules/comments/comments.php");
                }
            }
        }
        
        if ($SuperCache)
            $cache_obj->endCachingPage();
        
        include("footer.php");
        
    }

}