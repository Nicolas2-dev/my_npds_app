<?php

namespace Modules\News\Library;

use Modules\News\Contracts\NewsUltramodeInterface;


class NewsUltramodeManager implements NewsUltramodeInterface 
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

}
