<?php

namespace Modules\News\Library;

use Modules\News\Contracts\NewsTopicInterface;

/**
 * Undocumented class
 */
class NewsTopicManager implements NewsTopicInterface 
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
