<?php

namespace Modules\Push\Controllers\Front;


use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Date;
use Modules\Push\Support\Facades\Push as LPush;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


class PushNewShow extends FrontController
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
                LPush::push_header("suite");
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
                echo "document.write('" . links(LPush::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($hometext)))), "win", "html")) . "<br />');\n";
                
                if ($bodytext != "") {
                    echo "document.write('<br />');\n";
                    echo "document.write('" . links(LPush::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($bodytext)))), "win", "html")) . "<br />');\n";
                }

                if ($notes != "") {
                    echo "document.write('<br />');\n";
                    echo "document.write('" . links(LPush::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($notes)))), "win", "html")) . "');\n";
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

}
