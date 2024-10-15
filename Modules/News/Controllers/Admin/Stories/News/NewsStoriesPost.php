<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesPost extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'newarticle';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'adminStory';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('news', 'Articles');

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
     * @param [type] $type_pub
     * @param [type] $qid
     * @param [type] $uid
     * @param [type] $author
     * @param [type] $subject
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $notes
     * @param [type] $catid
     * @param [type] $ihome
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $date_debval
     * @param [type] $date_finval
     * @param [type] $epur
     * @return void
     */
    public function postStory($type_pub, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur)
    {
        if (!$date_debval)
            $date_debval = $dd_pub . ' ' . $dh_pub . ':01';

        if (!$date_finval)
            $date_finval = $fd_pub . ' ' . $fh_pub . ':01';

        if ($date_finval < $date_debval)
            $date_finval = $date_debval;

        $temp_new = mktime(substr($date_debval, 11, 2), substr($date_debval, 14, 2), 0, substr($date_debval, 5, 2), substr($date_debval, 8, 2), substr($date_debval, 0, 4));
        $temp = time();

        if ($temp > $temp_new)
            postStory("pub_immediate", $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur);
        else
            postStory("pub_automated", $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur);


        if ($uid == 1) 
            $author = '';
    
        if ($hometext == $bodytext) 
            $bodytext = '';
    
        $artcomplet = array('hometext' => $hometext, 'bodytext' => $bodytext, 'notes' => $notes);
        $rechcacheimage = '#cache/(a[i|c|n]\d+_\d+_\d+.[a-z]{3,4})\\\"#m';
    
        foreach ($artcomplet as $k => $artpartie) {
            preg_match_all($rechcacheimage, $artpartie, $cacheimages);
    
            foreach ($cacheimages[1] as $imagecache) {
                rename("cache/" . $imagecache, "modules/upload/upload/" . $imagecache);
    
                $$k = preg_replace($rechcacheimage, 'modules/upload/upload/\1"', $artpartie, 1);
            }
        }
    
        $subject = stripslashes(FixQuotes(str_replace('"', '&quot;', $subject)));
    
        $hometext = dataimagetofileurl($hometext, 'modules/upload/upload/ai');
        $bodytext = dataimagetofileurl($bodytext, 'modules/upload/upload/ac');
        $notes = dataimagetofileurl($notes, 'modules/upload/upload/an');
    
        $hometext = stripslashes(FixQuotes($hometext));
        $bodytext = stripslashes(FixQuotes($bodytext));
        $notes = stripslashes(FixQuotes($notes));
    
        if (($members == 1) and ($Mmembers == '')) 
            $ihome = '-127';
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) 
            $ihome = $Mmembers;
    
        if ($type_pub == 'pub_immediate') {
            $result = sql_query("INSERT INTO stories VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic','$author', '$notes', '$ihome', '0', '$date_finval','$epur')");
            
            Ecr_Log("security", "postStory (pub_immediate, $subject) by AID : $aid", "");
        } else {
            $result = sql_query("INSERT INTO autonews VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome','$date_debval','$date_finval','$epur')");
            
            Ecr_Log("security", "postStory (autonews, $subject) by AID : $aid", "");
        }
    
        if (($uid != 1) and ($uid != ''))
            sql_query("UPDATE users SET counter=counter+1 WHERE uid='$uid'");
    
        sql_query("UPDATE authors SET counter=counter+1 WHERE aid='$aid'");
    
        if (Config::get('npds.ultramode'))
            ultramode();
    
        deleteStory($qid);
    
        if ($type_pub == 'pub_immediate') {
    
            if (Config::get('npds.subscribe'))
                subscribe_mail("topic", $topic, '', $subject, '');
    
            // Cluster Paradise
            if (file_exists("modules/cluster-paradise/cluster-activate.php")) 
                include("modules/cluster-paradise/cluster-activate.php");
    
            if (file_exists("modules/cluster-paradise/cluster-M.php")) 
                include("modules/cluster-paradise/cluster-M.php");
    
            // Cluster Paradise
            // Réseaux sociaux
            if (file_exists('modules/App_twi/App_to_twi.php')) 
                include('modules/App_twi/App_to_twi.php');
    
            if (file_exists('modules/App_fbk/App_to_fbk.php')) 
                include('modules/App_twi/App_to_fbk.php');
            // Réseaux sociaux
        }
    
        redirect_url("admin.php?");
    }

}
