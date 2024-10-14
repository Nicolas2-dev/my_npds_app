<?php

namespace Modules\News\Library;

use Modules\News\Contracts\NewsAutomatedInterface;


class NewsAutomatedManager implements NewsAutomatedInterface 
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

}
