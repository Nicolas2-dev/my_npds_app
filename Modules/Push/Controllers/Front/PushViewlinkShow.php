<?php

namespace Modules\Push\Controllers\Front;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Push\Support\Facades\Push as LPush;
use Npds\Supercache\SuperCacheManager;
use Modules\Theme\Support\Facades\Theme;
use Modules\Npds\Support\Facades\Language;


class PushViewlinkShow extends FrontController
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
                $title = links(LPush::convert_nl(str_replace("'", "\'", $title), "win", "html"));
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
            
            LPush::push_footer();

            sql_free_result($result);
        }
        
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }    
    }

}
