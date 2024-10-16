<?php

namespace Modules\Newsletter\Controllers\Front;

use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Support\Facades\Url;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Mailer;
use Modules\Newsletter\Support\Facades\Newsletter;


/**
 * Undocumented class
 */
class Newsletters extends FrontController
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
     * @param [type] $var
     * @return void
     */
    public function subscribe($var)
    {
        if ($var != '') {

            echo '
            <h2>' . __d('newsletter', 'La lettre') . '</h2>
            <hr />
            <p class="lead mb-2">' . __d('newsletter', 'Gestion de vos abonnements') . ' : <strong>' . $var . '</strong></p>
                <form action="lnl.php" method="POST">
                ' . Spam::Q_spambot() . '
                <input type="hidden" name="email" value="' . $var . '" />
                <input type="hidden" name="op" value="subscribeOK" />
                <input type="submit" class="btn btn-outline-primary me-2" value="' . __d('newsletter', 'Valider') . '" />
                <a href="index.php" class="btn btn-outline-secondary">' . __d('newsletter', 'Retour en arrière') . '</a>
            </form>';

        } else {
            header("location: index.php");
        }
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $xemail
     * @return void
     */
    public function subscribe_ok($xemail)
    {
        global $stop;
    
        //anti_spambot
        if (!Spam::R_spambot($asb_question, $asb_reponse, "")) {
            Ecr_Log("security", "LNL Anti-Spam : email=" . $email, "");
            
            Url::redirect("index.php");
        }

        // include("header.php");
    
        if ($xemail != '') {

            Newsletter::SuserCheck($xemail);
    
            if ($stop == '') {
                $host_name  = Request::getip();
                $timeX      = date("Y-m-d H:m:s", time());
    
                // Troll Control
                list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
                
                if ($troll < 6) {
                    sql_query("INSERT INTO lnl_outside_users VALUES ('$xemail', '$host_name', '$timeX', 'OK')");
                    // Email validation + url to unsubscribe
    
                    $subject = html_entity_decode(__d('newsletter', 'La lettre'), ENT_COMPAT | ENT_HTML401, cur_charset) . ' / ' . Config::get('npds.sitename');
                    $message = __d('newsletter', 'Merci d\'avoir consacré du temps pour vous enregistrer.') . '<br /><br />' . __d('newsletter', 'Pour supprimer votre abonnement à notre lettre, merci d\'utiliser') . ' : <br />' . Config::get('npds.nuke_url') . '/lnl.php?op=unsubscribe&email=' . $xemail . '<br /><br />';
                    
                    include("signat.php");
    
                    Mailer::send_email($xemail, $subject, $message, '', true, 'html', '');
    
                    echo '
                    <div class="alert alert-success">' . __d('newsletter', 'Merci d\'avoir consacré du temps pour vous enregistrer.') . '</div>
                    <a href="index.php">' . __d('newsletter', 'Retour en arrière') . '</a>';
                } else {
                    $stop = __d('newsletter', 'Compte ou adresse IP désactivée. Cet émetteur a participé plus de x fois dans les dernières heures, merci de contacter le webmaster pour déblocage.') . "<br />";
                    Newsletter::error_handler($stop);
                }
            } else {
                Newsletter::error_handler($stop);
                }
        } else {
            Newsletter::error_handler(__d('newsletter', 'Cette donnée ne doit pas être vide.') . "<br />");
        }
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $xemail
     * @return void
     */
    public function unsubscribe($xemail)
    {
        if ($xemail != '') {
            if ((!$xemail) || ($xemail == '') || (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $xemail))) {
                header("location: index.php");
            }
    
            if (strrpos($xemail, ' ') > 0) {
                header("location: index.php");
            }
    
            if (sql_num_rows(sql_query("SELECT email FROM lnl_outside_users WHERE email='$xemail'")) > 0) {
                $host_name = Request::getip();
    
                // Troll Control
                list($troll) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM lnl_outside_users WHERE (host_name='$host_name') AND (to_days(now()) - to_days(date) < 3)"));
                
                if ($troll < 6) {
                    sql_query("UPDATE lnl_outside_users SET status='NOK' WHERE email='$xemail'");

                    echo '
                    <div class="alert alert-success">' . __d('newsletter', 'Merci') . '</div>
                    <a href="index.php">' . __d('newsletter', 'Retour en arrière') . '</a>';

                } else {
                    $stop = __d('newsletter', 'Compte ou adresse IP désactivée. Cet émetteur a participé plus de x fois dans les dernières heures, merci de contacter le webmaster pour déblocage.') . "<br />";
                    
                    Newsletter::error_handler($stop);
                }
            } else {
                Url::redirect("index.php");
            }
        } else {
            Url::redirect("index.php");
        }
    }

}