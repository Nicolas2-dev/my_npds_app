<?php

namespace Modules\News\Controllers\Front;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Modules\News\Support\Facades\News;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Npds\Supercache\SuperCacheManager;
use Modules\Theme\Support\Facades\Theme;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;
use Modules\News\Support\Facades\NewsTopic;
use Modules\Users\Support\Facades\UserPopover;


/**
 * Undocumented class
 */
class NewsArticle extends FrontController
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
        if (!isset($sid) && !isset($tid)) {
            header("Location: index.php");
        }
        
        if (!isset($archive)) {
            $archive = 0;
        }
        
        $xtab = (!$archive)
            ? News::news_aff("libre", "WHERE sid='$sid'", 1, 1)
            : News::news_aff("archive", "WHERE sid='$sid'", 1, 1);
        
        list($sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = $xtab[0];
        
        if (!$aid) {
            header("Location: index.php");
        }
        
        sql_query("UPDATE stories SET counter=counter+1 WHERE sid='$sid'");

        // Include cache manager
        global $SuperCache;
        
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else
            $cache_obj = new SuperCacheEmpty();
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {

            $title      = Language::aff_langue(stripslashes($title));
            $hometext   = Code::aff_code(Language::aff_langue(stripslashes($hometext)));
            $bodytext   = Code::aff_code(Language::aff_langue(stripslashes($bodytext)));
            $notes      = Code::aff_code(Language::aff_langue(stripslashes($notes)));
        
            if ($notes != '') {
                $notes = '<div class="note blockquote">' . __d('news', 'Note') . ' : ' . $notes . '</div>';
            }
        
            $bodytext = $bodytext == ''
                ? Metalang::meta_lang($hometext . '<br />' . $notes)
                : Metalang::meta_lang($hometext . '<br />' . $bodytext . '<br />' . $notes);
        
            if ($informant == '') {
                $informant = Config::get('npds.anonymous');
            }
        
            NewsTopic::getTopics($sid);
        
            if ($catid != 0) {
                $resultx = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
                list($title1) = sql_fetch_row($resultx);
        
                $title = '<a href="index.php?op=newindex&amp;catid=' . $catid . '"><span>' . Language::aff_langue($title1) . '</span></a> : ' . $title;
            }
        
            $boxtitle = __d('news', 'Liens relatifs');
            $boxstuff = '<ul>';
        
            $result = sql_query("SELECT name, url FROM related WHERE tid='$topic'");
            while (list($name, $url) = sql_fetch_row($result)) {
                $boxstuff .= '<li><a href="' . $url . '" target="_blank"><span>' . $name . '</span></a></li>';
            }
        
            $boxstuff .= '
                </ul>
                <ul>
                    <li><a href="search.php?topic=' . $topic . '" >' . __d('news', 'En savoir plus à propos de') . ' : </a><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . Language::aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . Language::aff_langue($topicname) . '</span></span></li>
                    <li><a href="search.php?member=' . $informant . '" >' . __d('news', 'Article de') . ' ' . $informant . '</a> ' . UserPopover::userpopover($informant, 36, '') . '</li>
                </ul>
                <div><span class="fw-semibold">' . __d('news', 'L\'article le plus lu à propos de') . ' : </span><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . Language::aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . Language::aff_langue($topicname) . '</span></span></div>';
        
            $xtab = News::news_aff("big_story", "WHERE topic=$topic", 1, 1);
        
            list($topstory, $ttitle) = $xtab[0];
        
            $boxstuff .= '
                <ul>
                    <li><a href="article.php?sid=' . $topstory . '" >' . Language::aff_langue($ttitle) . '</a></li>
                </ul>
                <div><span class="fw-semibold">' . __d('news', 'Les dernières nouvelles à propos de') . ' : </span><span class="h5"><span class="badge bg-secondary" title="' . $topicname . '<hr />' . Language::aff_langue($topictext) . '" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="right">' . Language::aff_langue($topicname) . '</span></span></div>';
        
            $xtab = (!$archive)
                ? News::news_aff("libre", "WHERE topic=$topic AND archive='0' ORDER BY sid DESC LIMIT 0,5", 0, 5)
                : News::news_aff("archive", "WHERE topic=$topic AND archive='1' ORDER BY sid DESC LIMIT 0,5", 0, 5);
        
            $story_limit = 0;
            $boxstuff .= '<ul>';
        
            while (($story_limit < 5) and ($story_limit < sizeof($xtab))) {
                list($sid1, $catid1, $aid1, $title1) = $xtab[$story_limit];
        
                $story_limit++;
                $title1 = Language::aff_langue(addslashes($title1));
        
                $boxstuff .= '<li><a href="article.php?sid=' . $sid1 . '&amp;archive=' . $archive . '" >' . Language::aff_langue(stripslashes($title1)) . '</a></li>';
            }
        
            $boxstuff .= '
                </ul>
                <p align="center">
                    <a href="print.php?sid=' . $sid . '&amp;archive=' . $archive . '" ><i class="fa fa-print fa-2x me-3" title="' . __d('news', 'Page spéciale pour impression') . '" data-bs-toggle="tooltip"></i></a>
                    <a href="friend.php?op=FriendSend&amp;sid=' . $sid . '&amp;archive=' . $archive . '"><i class="fa fa-2x fa-at" title="' . __d('news', 'Envoyer cet article à un ami') . '" data-bs-toggle="tooltip"></i></a>
                </p>';
        
            if (!$archive) {
                $previous_tab   = News::news_aff("libre", "WHERE sid<'$sid' ORDER BY sid DESC ", 0, 1);
                $next_tab       = News::news_aff("libre", "WHERE sid>'$sid' ORDER BY sid ASC ", 0, 1);
            } else {
                $previous_tab   = News::news_aff("archive", "WHERE sid<'$sid' ORDER BY sid DESC", 0, 1);
                $next_tab       = News::news_aff("archive", "WHERE sid>'$sid' ORDER BY sid ASC ", 0, 1);
            }
        
            if (array_key_exists(0, $previous_tab)) {
                list($previous_sid) = $previous_tab[0];
            } else {
                $previous_sid = 0;
            }
        
            if (array_key_exists(0, $next_tab)) {
                list($next_sid) = $next_tab[0];
            } else {
                $next_sid = 0;
            }
        
            Theme::themearticle($aid, $informant, $time, $title, $bodytext, $topic, $topicname, $topicimage, $topictext, $sid, $previous_sid, $next_sid, $archive);
        
            // theme sans le système de commentaire en meta-mot !
            if (!function_exists("Caff_pub")) {
                if (file_exists("modules/comments/article.conf.php")) {
                    include("modules/comments/article.conf.php");
                    include("modules/comments/comments.php");
                }
            }
        }
        
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}