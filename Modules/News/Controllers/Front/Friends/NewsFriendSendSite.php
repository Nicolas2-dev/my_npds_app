<?php

namespace Modules\News\Controllers\Front;

use Modules\Npds\Core\FrontController;


/**
 * Undocumented class
 */
class NewsFriendSendSite extends FrontController
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
     * @param [type] $yname
     * @param [type] $ymail
     * @param [type] $fname
     * @param [type] $fmail
     * @param [type] $asb_question
     * @param [type] $asb_reponse
     * @return void
     */
    public function SendSite($yname, $ymail, $fname, $fmail, $asb_question, $asb_reponse)
    {
        global $user;
    
        if (!$user) {
            //anti_spambot
            if (!R_spambot($asb_question, $asb_reponse, '')) {
                Ecr_Log('security', "Friend Anti-Spam : name=" . $yname . " / mail=" . $ymail, '');
                redirect_url("index.php");
                die();
            }
        }
    
        $subject = html_entity_decode(__d('news', 'Site à découvrir : '), ENT_COMPAT | ENT_HTML401, cur_charset) . Config::get('npds.sitename');
    
        $fname = removeHack($fname);
        $message = __d('news', 'Bonjour') . " $fname :\n\n" . __d('news', 'Votre ami') . " $yname " . __d('news', 'a trouvé notre site') . Config::get('npds.sitename') . __d('news', 'intéressant et a voulu vous le faire connaître.') . "\n\n".Config::get('npds.sitename')." : <a href=\"Config::get('npds.nuke_url')\">Config::get('npds.nuke_url')</a>\n\n";
        
        include("signat.php");
    
        $fmail = removeHack($fmail);
        $subject = removeHack($subject);
        $message = removeHack($message);
        $yname = removeHack($yname);
        $ymail = removeHack($ymail);
        $stop = false;
    
        if ((!$fmail) || ($fmail == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $fmail)))   
            $stop = true;
    
        if ((!$ymail) || ($ymail == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $ymail))) 
            $stop = true;
    
        if (!$stop)
            send_email($fmail, $subject, $message, $ymail, false, 'html', '');
        else
            $fname = '';
    
        Header("Location: friend.php?op=SiteSent&fname=$fname");
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $fname
     * @return void
     */
    public function SiteSent($fname)
    {
        if ($fname == '')
            echo '
                <div class="alert alert-danger lead" role="alert">
                    <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
                    ' . __d('news', 'Erreur : Email invalide') . '
                </div>';
        else
            echo '
            <div class="alert alert-success lead" role="alert">
                <i class="fa fa-exclamation-triangle fa-lg"></i>&nbsp;
                ' . __d('news', 'Nos références ont été envoyées à ') . ' ' . $fname . ', <br />
                <strong>' . __d('news', 'Merci de nous avoir recommandé') . '</strong>
            </div>';
    }

}
