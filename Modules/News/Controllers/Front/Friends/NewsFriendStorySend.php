<?php

namespace Modules\News\Controllers\Front;

use Modules\Npds\Core\FrontController;


/**
 * Undocumented class
 */
class NewsFriendStorySend extends FrontController
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
     * @param [type] $sid
     * @param [type] $yname
     * @param [type] $ymail
     * @param [type] $fname
     * @param [type] $fmail
     * @param [type] $archive
     * @param [type] $asb_question
     * @param [type] $asb_reponse
     * @return void
     */
    public function SendStory($sid, $yname, $ymail, $fname, $fmail, $archive, $asb_question, $asb_reponse)
    {
        global $user;
    
        if (!$user) {
            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, '')) {
                Ecr_Log('security', "Send-Story Anti-Spam : name=" . $yname . " / mail=" . $ymail, '');
                redirect_url("index.php");
                die();
            }
        }

        $result2 = sql_query("SELECT title, time, topic FROM stories WHERE sid='$sid'");
        list($title, $time, $topic) = sql_fetch_row($result2);
    
        $result3 = sql_query("SELECT topictext FROM topics WHERE topicid='$topic'");
        list($topictext) = sql_fetch_row($result3);
    
        $subject = html_entity_decode(__d('news', 'Article intéressant sur'), ENT_COMPAT | ENT_HTML401, cur_charset) . Config::get('npds.sitename');
        $fname = removeHack($fname);
        $message = __d('news', 'Bonjour') . " $fname :\n\n" . __d('news', 'Votre ami') . " $yname " . __d('news', 'a trouvé cet article intéressant et a souhaité vous l\'envoyer.') . "\n\n" . aff_langue($title) . "\n" . __d('news', 'Date :') . " $time\n" . __d('news', 'Sujet : ') . " " . aff_langue($topictext) . "\n\n" . __d('news', 'L\'article') . " : <a href=\"Config::get('npds.nuke_url')/article.php?sid=$sid&amp;archive=$archive\">Config::get('npds.nuke_url')/article.php?sid=$sid&amp;archive=$archive</a>\n\n";
        
        // include("signat.php");
    
        $fmail = removeHack($fmail);
        $subject = removeHack($subject);
        $message = removeHack($message);
        $yname = removeHack($yname);
        $ymail = removeHack($ymail);
    
        $stop = false;
    
        if ((!$fmail) || ($fmail == "") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $fmail))) 
            $stop = true;
    
        if ((!$ymail) || ($ymail == "") || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $ymail))) 
            $stop = true;
    
        if (!$stop)
            send_email($fmail, $subject, $message, $ymail, false, 'html', '');
        else {
            $title = '';
            $fname = '';
        }
    
        $title = urlencode(aff_langue($title));
        $fname = urlencode($fname);
    
        Header("Location: friend.php?op=StorySent&title=$title&fname=$fname");
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $title
     * @param [type] $fname
     * @return void
     */
    public function StorySent($title, $fname)
    {
        $title = urldecode($title);
        $fname = urldecode($fname);
    
        if ($fname == '')
            echo '<div class="alert alert-danger">' . __d('news', 'Erreur : Email invalide') . '</div>';
        else
            echo '<div class="alert alert-success">' . __d('news', 'L\'article') . ' <strong>' . stripslashes($title) . '</strong> ' . __d('news', 'a été envoyé à') . '&nbsp;' . $fname . '<br />' . __d('news', 'Merci') . '</div>';
    }

}
