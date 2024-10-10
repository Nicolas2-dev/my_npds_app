<?php

namespace Modules\Banners\Controllers\Front;

use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Mailer;

/**
 * Undocumented class
 */
class BannerEmailStats extends FrontController
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
     * @param [type] $login
     * @param [type] $cid
     * @param [type] $bid
     * @return void
     */
    public function EmailStats($login, $cid, $bid)
    {
        $result = sql_query("SELECT login FROM bannerclient WHERE cid='$cid'");
        list($loginBD) = sql_fetch_row($result);
    
        if ($login == $loginBD) {
            $result2 = sql_query("SELECT name, email FROM bannerclient WHERE cid='$cid'");
            list($name, $email) = sql_fetch_row($result2);
    
            if ($email == '') {
                header_page();
    
                echo "<p align=\"center\"><br />" . __d('banners', 'Les statistiques pour la bannières ID') . " : $bid " . __d('banners', 'ne peuvent pas être envoyées.') . "<br /><br />
                " . __d('banners', 'Email non rempli pour : ') . " $name<br /><br /><a href=\"javascript:history.go(-1)\" >" . __d('banners', 'Retour en arrière') . "</a></p>";
    
                footer_page();
            } else {
                $result = sql_query("SELECT bid, imptotal, impmade, clicks, imageurl, clickurl, date FROM banner WHERE bid='$bid' AND cid='$cid'");
                list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($result);
                $percent = $impmade == 0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
    
                if ($imptotal == 0) {
                    $left = __d('banners', 'Illimité');
                    $imptotal = __d('banners', 'Illimité');
                } else
                    $left = $imptotal - $impmade;
    
                $fecha = date(__d('banners', 'dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600));
    
                $subject = html_entity_decode(__d('banners', 'Bannières - Publicité'), ENT_COMPAT | ENT_HTML401, cur_charset) . ' : ' . Config::get('npds.sitename');
                $message  = "Client : $name\n" . __d('banners', 'Bannière') . " ID : $bid\n" . __d('banners', 'Bannière') . " Image : $imageurl\n" . __d('banners', 'Bannière') . " URL : $clickurl\n\n";
                $message .= "Impressions " . __d('banners', 'Réservées') . " : $imptotal\nImpressions " . __d('banners', 'Réalisées') . " : $impmade\nImpressions " . __d('banners', 'Restantes') . " : $left\nClicks " . __d('banners', 'Reçus') . " : $clicks\nClicks " . __d('banners', 'Pourcentage') . " : $percent%\n\n";
                $message .= __d('banners', 'Rapport généré le') . ' : ' . "$fecha\n\n";
    
                include("signat.php");
    
                Mailer::send_email($email, $subject, $message, '', true, 'html', '');
    
                header_page();
    
                echo '
                <div class="card bg-light">
                    <div class="card-body"
                    <p>' . $fecha . '</p>
                    <p>' . __d('banners', 'Les statistiques pour la bannières ID') . ' : ' . $bid . ' ' . __d('banners', 'ont été envoyées.') . '</p>
                    <p>' . $email . ' : Client : ' . $name . '</p>
                    <p><a href="javascript:history.go(-1)" class="btn btn-primary">' . __d('banners', 'Retour en arrière') . '</a></p>
                    </div>
                </div>';
            }
        } else {
            header_page();
    
            echo "<p align=\"center\"><br />" . __d('banners', 'Identifiant incorrect !') . "<br /><br />" . __d('banners', 'Merci de') . " <a href=\"banners.php?op=login\" class=\"noir\">" . __d('banners', 'vous reconnecter.') . "</a></p>";
        }
    
        footer_page();
    }

}
