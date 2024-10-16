<?php

namespace Modules\Push\Controllers\Front;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Date;
use Modules\Push\Support\Facades\Push;
use Npds\Supercache\SuperCacheManager;
use Modules\Theme\Support\Facades\Theme;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


class Pushs extends FrontController
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
        global $options;

        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
            Push::push_header("menu");
            Push::push_menu();

            if (substr($options, 0, 1) == 1) {
                Push::push_news();
            }

            if (substr($options, 1, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                Push::push_faq();
            }

            if (substr($options, 2, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                Push::push_poll();
            }

            if (substr($options, 3, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                Push::push_members();
            }

            if (substr($options, 4, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                Push::push_links();
            }

            Push::push_menu();
            echo "document.write('</td></tr><tr><td align=\"center\">');\n";
            echo "document.write('<a href=\"http://www.App.org\">App Push System</a>');\n";
            Push::push_footer();
        }
            
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

    /**
     * case "new_show": => new_show($sid, $offset);
     * 
     * Undocumented function
     *
     * @param [type] $sid
     * @param [type] $offset
     * @return void
     */
    public function new_show($sid, $offset)
    {
        global $follow_links, $datetime;

        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {

            $result = sql_query("SELECT hometext, bodytext, notes, title, time, informant, topic FROM stories WHERE sid='$sid'");

            if ($result) {
                Push::push_header("suite");
                list($hometext, $bodytext, $notes, $title, $time, $informant, $topic) = sql_fetch_row($result);

                sql_free_result($result);

                $result = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");

                if ($result) {
                    list($topictext) = sql_fetch_row($result);
                }

                $title = str_replace("'", "\'", $title);
                echo "document.write('<span style=\"font-size: 11px;\"><b>.:|<a href=\"Config::get('npds.nuke_url')/article.php?sid=$sid\" target=\"_blank\">" . Language::aff_langue($title) . "</a>|:.</b></span><br />');\n";

                Date::formatTimestamp($time);

                $topictext = str_replace("'", "\'", $topictext);

                echo "document.write('" . __d('push', 'Posted by') . " <b>$informant</b> : $datetime (" . htmlspecialchars($topictext, ENT_COMPAT | ENT_HTML401, cur_charset) . ")');\n";
                echo "document.write('<br /><br />');\n";
                echo "document.write('" . links(Push::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($hometext)))), "win", "html")) . "<br />');\n";
                
                if ($bodytext != "") {
                    echo "document.write('<br />');\n";
                    echo "document.write('" . links(Push::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($bodytext)))), "win", "html")) . "<br />');\n";
                }

                if ($notes != "") {
                    echo "document.write('<br />');\n";
                    echo "document.write('" . links(Push::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($notes)))), "win", "html")) . "');\n";
                }

                echo "document.write('<br /><span style=\"font-size: 11px;\">.: <a href=\"javascript: history.go(0)\">" . __d('push', 'Home') . "</a> :.</span>');\n";
                push_footer();
            }

            sql_free_result($result);
        }
            
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

    /**
     * case "faq_show": => faq_show($id_cat);
     * 
     * Undocumented function
     *
     * @param [type] $id_cat
     * @return void
     */
    public function faq_show($id_cat)
    {
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {    

            Push::push_header("suite");

            $result = sql_query("SELECT categories FROM faqcategories WHERE id_cat='$id_cat'");
            list($categories) = sql_fetch_row($result);

            $categories = str_replace("'", "\'", $categories);
            echo "document.write('<p align=\"center\"><a name=\"$id\"></a><b>" . Language::aff_langue($categories) . "</b></p>');\n";

            $result = sql_query("SELECT id, id_cat, question, answer FROM faqanswer WHERE id_cat='$id_cat'");

            while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
                $question = str_replace("'", "\'", $question);

                echo "document.write('<b>" . aff_langue($question) . "</b>');\n";
                echo "document.write('<p align=\"justify\">" . links(Push::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($answer)))), "win", "html")) . "</p><br />');\n";
            }

            echo "document.write('.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">" . __d('push', 'Home') . "</a> :.');\n";

            Push::push_footer();

            sql_free_result($result);
        }
            
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

    /**
     * case "viewlink": => viewlink_show($cid, $min);
     * 
     * Undocumented function
     *
     * @param [type] $cid
     * @param [type] $min
     * @return void
     */
    public function viewlink_show($cid, $min)
    {
        global $follow_links, $push_view_perpage, $push_orderby;
        
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
            push_header("suite");

            if (!isset($min)) {
                $min = 0;
            }

            Config::set('npds.perpage', $push_view_perpage);

            $orderby = "title " . $push_orderby;

            $result = sql_query("SELECT title FROM links_categories WHERE cid='$cid'");
            list($title) = sql_fetch_row($result);

            $title = str_replace("'", "\'", $title);

            echo "document.write('<span  style=\"font-size: 11px;\"><b>" . Languege::aff_langue($title) . "</b></span>');\n";

            $subresult = sql_query("SELECT sid, title FROM links_subcategories WHERE cid='$cid' ORDER BY $orderby");
            $numrows = sql_num_rows($subresult);

            if ($numrows != 0) {
                echo "document.write('<b> / Sub-Cat</b><br />');\n";

                while (list($sid, $title) = sql_fetch_row($subresult)) {
                    $result2 = sql_query("SELECT * FROM links_links WHERE sid='$sid'");
                    $numrows = sql_num_rows($result2);

                    $title = str_replace("'", "\'", $title);
                    echo "document.write('<li><a href=javascript:onclick=register(\"App-push\",\"op=viewslink&sid=$sid\"); style=\"font-size: 11px;\">" . Language::aff_langue($title) . "</a> ($numrows)</li>');\n";
                }

                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
            } else {
                echo "document.write('<br />');\n";
            }

            $result = sql_query("SELECT lid, title FROM links_links WHERE cid='$cid' AND sid=0 ORDER BY $orderby LIMIT $min,Config::get('npds.perpage')");
            $fullcountresult = sql_query("SELECT lid, title FROM links_links WHERE cid='$cid' AND sid=0");

            $totalselectedlinks = sql_num_rows($fullcountresult);

            while (list($lid, $title) = sql_fetch_row($result)) {
                $title = links(Push::convert_nl(str_replace("'", "\'", $title), "win", "html"));
                echo "document.write('<li><a href=\"Config::get('npds.nuke_url')/links.php?op=visit&amp;lid=$lid\" target=\"_blank\" style=\"font-size: 11px;\">" . Language::aff_langue($title) . "</a></li><br />');\n";
            }

            if (($totalselectedlinks - $min) > Config::get('npds.perpage')) {
                $min = $min + Config::get('npds.perpage');

                if ($ibid = Theme::theme_image("box/right.gif")) {
                    $imgtmp = $ibid;
                } else {
                    $imgtmp = "assets/images/download/right.gif";
                }

                echo "document.write('<a href=javascript:onclick=register(\"App-push\",\"op=viewlink&cid=$cid&min=$min\"); style=\"font-size: 11px;\"><img src=\"Config::get('npds.nuke_url')/$imgtmp\" border=\"0\" alt=\"\" align=\"center\" /></a><br />');\n";
            }

            echo "document.write('<br />.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">" . __d('push', 'Home') . "</a> :.');\n";
            
            Push::push_footer();

            sql_free_result($result);
        }
        
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }    
    }

    /**
     * case "viewslink": =>viewslink_show($sid, $min);
     * 
     * Undocumented function
     *
     * @param [type] $sid
     * @param [type] $min
     * @return void
     */
    public function viewslink_show($sid, $min)
    {
        global $follow_links, $push_view_perpage, $push_orderby;
        
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
            Push::push_header("suite");

            if (!isset($min)) {
                $min = 0;
            }

            Config::set('npds.perpage', $push_view_perpage);
            $orderby = "title " . $push_orderby;

            $result = sql_query("SELECT cid, title FROM links_subcategories WHERE sid='$sid'");
            list($cid, $stitle) = sql_fetch_row($result);

            $result2 = sql_query("SELECT cid, title FROM links_categories WHERE cid='$cid'");
            list($cid, $title) = sql_fetch_row($result2);

            $title = str_replace("'", "\'", $title);
            $stitle = str_replace("'", "\'", $stitle);

            echo "document.write('<span style=\"font-size: 11px;\"><b>" . Language::aff_langue($title) . " / SubCat : " . Language::aff_langue($stitle) . "</b>'</span>);\n";

            $result = sql_query("SELECT lid, title FROM links_links WHERE cid='$cid' AND sid='$sid' ORDER BY $orderby LIMIT $min,Config::get('npds.perpage')");
            $fullcountresult = sql_query("SELECT lid, title FROM links_links WHERE cid='$cid' AND sid='$sid'");
            $totalselectedlinks = sql_num_rows($fullcountresult);

            echo "document.write('<br />');\n";

            while (list($lid, $title) = sql_fetch_row($result)) {
                $title = links(Push::convert_nl(str_replace("'", "\'", $title), "win", "html"));
                echo "document.write('<li><a href=\"Config::get('npds.nuke_url')/links.php?op=visit&amp;lid=$lid\" target=\"_blank\" style=\"font-size: 11px;\">" . Language::aff_langue($title) . "</a></li><br />');\n";
            }

            if (($totalselectedlinks - $min) > Config::get('npds.perpage')) {
                $min = $min + Config::get('npds.perpage');

                if ($ibid = Theme::theme_image("box/right.gif")) {
                    $imgtmp = $ibid;
                } else {
                    $imgtmp = "assets/images/download/right.gif";
                }

                echo "document.write('<a href=javascript:onclick=register(\"App-push\",\"op=viewlink&cid=$cid&min=$min\"); style=\"font-size: 11px;\"><img src=\"Config::get('npds.nuke_url')/$imgtmp\" border=\"0\" alt=\"\" align=\"center\" /></a><br />');\n";
            }

            echo "document.write('<br />.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">" . __d('push', 'Home') . "</a> :.');\n";
            
            Push::push_footer();

            sql_free_result($result);
        }
        
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
