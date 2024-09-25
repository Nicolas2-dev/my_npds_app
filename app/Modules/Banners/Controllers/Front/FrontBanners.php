<?php

namespace App\Controllers\Front;

use Npds\Http\Request;
use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Front extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // settype($op, 'string');

        // switch ($op) {
        //     case 'click':
        //         clickbanner($bid);
        //         break;
        
        //     case 'login':
        //         clientlogin();
        //         break;
        
        //     case 'Ok':
        //         bannerstats($login, $pass);
        //         break;
        
        //     case 'Changer':
        //         change_banner_url_by_client($login, $pass, $cid, $bid, $url);
        //         break;
        
        //     case 'EmailStats':
        //         EmailStats($login, $cid, $bid);
        //         break;
        
        //     default:
        //         if (Config::get('npds.banners'))
        //             viewbanner();
        //         else
        //             redirect_url('index.php');
        //         break;
        // }
    }

    function viewbanner()
    {
        $okprint        = false;
        $while_limit    = 3;
        $while_cpt      = 0;
    
        $bresult = sql_query("SELECT bid FROM banner WHERE userlevel!='9'");
        $numrows = sql_num_rows($bresult);
    
        while ((!$okprint) and ($while_cpt < $while_limit)) {

            // More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar
            if ($numrows > 0) {
                mt_srand((float) microtime() * 1000000);
                $bannum = mt_rand(0, $numrows);
            } else {
                break;
            }
    
            $bresult2 = sql_query("SELECT bid, userlevel FROM banner WHERE userlevel!='9' LIMIT $bannum,1");
            list($bid, $userlevel) = sql_fetch_row($bresult2);
    
            if ($userlevel == 0) {
                $okprint = true;
            } else {
                if ($userlevel == 1) {
                    if (Auth::secur_static("member")) {
                        $okprint = true;
                    }
                }
    
                if ($userlevel == 3) {
                    if (Auth::secur_static("admin")) {
                        $okprint = true;
                    }
                }
            }
    
            $while_cpt = $while_cpt + 1;
        }
    
        // Le risque est de sortir sans un BID valide
        if (!isset($bid)) {
            $rowQ1 = Q_Select("SELECT bid FROM banner WHERE userlevel='0' LIMIT 0,1", 86400);
    
            if ($rowQ1) {
                $myrow = $rowQ1[0]; // erreur à l'install quand on n'a pas de banner dans la base ....
                $bid = $myrow['bid'];
                $okprint = true;
            }
        }
    
        if ($okprint) {
    
            $myhost = Request::getip();
    
            if (Config::get('npds.myIP') != $myhost) {
                sql_query("UPDATE banner SET impmade=impmade+1 WHERE bid='$bid'");
            }
    
            if (($numrows > 0) and ($bid)) {

                $aborrar = sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl, date FROM banner WHERE bid='$bid'");
                list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($aborrar);
    
                if ($imptotal == $impmade) {
                    sql_query("INSERT INTO bannerfinish VALUES (NULL, '$cid', '$impmade', '$clicks', '$date', now())");
                    sql_query("DELETE FROM banner WHERE bid='$bid'");
                }
    
                if ($imageurl != '') {
                    echo '<a href="banners.php?op=click&amp;bid=' . $bid . '" target="_blank"><img class="img-fluid" src="' . Language::aff_langue($imageurl) . '" alt="" /></a>';
                } else {
                    if (stristr($clickurl, '.txt')) {
                        if (file_exists($clickurl)) {
                            include_once($clickurl);
                        }
                    } else {
                        echo $clickurl;
                    }
                }
            }
        }
    }
    
    function clickbanner($bid)
    {
        $bresult = sql_query("SELECT clickurl FROM banner WHERE bid='$bid'");
        list($clickurl) = sql_fetch_row($bresult);
    
        sql_query("UPDATE banner SET clicks=clicks+1 WHERE bid='$bid'");
        sql_free_result($bresult);
    
        if ($clickurl == '') {
            $clickurl = Config::get('npds.nuke_url');
        }
    
        Header("Location: " . Language::aff_langue($clickurl));
    }
    
    function clientlogin()
    {
        header_page();
    
        echo '
            <div class="card card-body mb-3">
                <h3 class="mb-4"><i class="fas fa-sign-in-alt fa-lg me-3 align-middle"></i>' . __d('banners', 'Connexion') . '</h3>
                <form id="loginbanner" action="banners.php" method="post">
                    <fieldset>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="login" name="login" maxlength="10" required="required" />
                        <label for="login">' . __d('banners', 'Identifiant') . '</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="pass" name="pass" maxlength="10" required="required" />
                        <label for="pass">' . __d('banners', 'Mot de passe') . '</label>
                        <span class="help-block">' . __d('banners', 'Merci de saisir vos informations') . '</span>
                    </div>
                    <input type="hidden" name="op" value="Ok" />
                    <button class="btn btn-primary my-3" type="submit">' . __d('banners', 'Valider') . '</button>
                    </div>
                    </fieldset>
                </form>
            </div>';
    
        $arg1 = 'var formulid=["loginbanner"];';
    
        Css::adminfoot('fv', '', $arg1, 'no');
    
        footer_page();
    }
    
    function IncorrectLogin()
    {
        header_page();
        echo '<div class="alert alert-danger lead">
                ' . __d('banners', 'Identifiant incorrect !') . '
                <br />
                <button class="btn btn-secondary mt-2" onclick="javascript:history.go(-1)" >
                    ' . __d('banners', 'Retour en arrière') . '
                </button>
            </div>';
        footer_page();
    }
    
    function header_page()
    {
        include_once("modules/upload/upload.conf.php");

        include("storage/meta/meta.php");
    
        if ($url_upload_css) {
            $url_upload_cssX = str_replace('style.css', Config::get('npds.language') . '-style.css', $url_upload_css);
    
            if (is_readable($url_upload . $url_upload_cssX)) {
                $url_upload_css = $url_upload_cssX;
            }
    
            print("<link href=\"" . $url_upload . $url_upload_css . "\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
        }

        if (file_exists('themes/default/include/header_head.inc')) {
            include('themes/default/include/header_head.inc');
        }
    
        if (file_exists('themes/' . Config::get('npds.Default_Theme') . '/include/header_head.inc')) {
            include('themes/' . Config::get('npds.Default_Theme') . '/include/header_head.inc');
        }
    
        if (file_exists('themes/' . Config::get('npds.Default_Theme') . '/style/style.css')) {
            echo '<link href="themes/' . Config::get('npds.Default_Theme') . '/style/style.css" rel="stylesheet" type=\"text/css\" media="all" />';
        }
    
        echo '
        </head>
        <body style="margin-top:64px;">
            <div class="container-fluid">
            <nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-primary">
                <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><i class="fa fa-home fa-lg me-2"></i></a>
                <span class="navbar-text">' . __d('banners', 'Bannières - Publicité') . '</span>
                </div>
            </nav>
            <h2 class="mt-4">' . __d('banners', 'Bannières - Publicité') . ' @ ' . Config::get('npds.Titlesitename') . '</h2>
            <p align="center">';
    }
    
    function footer_page()
    {
        include('themes/default/include/footer_after.inc');
    
        echo '</p>
            </div>
        </body>
        </html>';
    }
    
    function bannerstats($login, $pass)
    {
        $result = sql_query("SELECT cid, name, passwd FROM bannerclient WHERE login='$login'");
        list($cid, $name, $passwd) = sql_fetch_row($result);
    
        if ($login == '' and $pass == '' or $pass == '')
            IncorrectLogin();
        else {
            if ($pass == $passwd) {
                header_page();
    
                echo '
                <h3>' . __d('banners', 'Bannières actives pour') . ' ' . $name . '</h3>
                <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
                    <thead>
                    <tr>
                        <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'ID') . '</th>
                        <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Réalisé') . '</th>
                        <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Impressions') . '</th>
                        <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Imp. restantes') . '</th>
                        <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Clics') . '</th>
                        <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">% ' . __d('banners', 'Clics') . '</th>
                        <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('banners', 'Fonctions') . '</th>
                    </tr>
                    </thead>
                    <tbody>';
    
                $result = sql_query("SELECT bid, imptotal, impmade, clicks, date FROM banner WHERE cid='$cid'");
    
                while (list($bid, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
                    $percent = $impmade == 0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
                    $left = $imptotal == 0 ? __d('banners', 'Illimité') : $imptotal - $impmade;
    
                    echo '
                    <tr>
                        <td>' . $bid . '</td>
                        <td>' . $impmade . '</td>
                        <td>' . $imptotal . '</td>
                        <td>' . $left . '</td>
                        <td>' . $clicks . '</td>
                        <td>' . $percent . '%</td>
                        <td><a href="banners.php?op=EmailStats&amp;login=' . $login . '&amp;cid=' . $cid . '&amp;bid=' . $bid . '" ><i class="far fa-envelope fa-lg me-2 tooltipbyclass" data-bs-placement="top" title="E-mail Stats"></i></a></td>
                    </tr>';
                }
    
                echo '
                    </tbody>
                </table>
                <div class="lead my-3">
                    <a href="' . Config::get('npds.nuke_url') . '" target="_blank">' . Config::get('npds.sitename') . '</a>
                </div>';
    
                $result = sql_query("SELECT bid, imageurl, clickurl FROM banner WHERE cid='$cid'");
    
                while (list($bid, $imageurl, $clickurl) = sql_fetch_row($result)) {
                    $numrows = sql_num_rows($result);
    
                    echo '<div class="card card-body mb-3">';
    
                    if ($imageurl != '') {
                        echo '<p><img src="' . Language::aff_langue($imageurl) . '" class="img-fluid" />'; //pourquoi aff_langue ??
                    } else {
                        echo '<p>';
                        echo $clickurl;
                    }
    
                    echo '<h4 class="mb-2">'. __d('banners', 'Banner ID : ') . $bid . '</h4>';
    
                    if ($imageurl != '') {
                        echo '<p>' . __d('banners', 'Cette bannière est affichée sur l\'url') . ' : <a href="' . Language::aff_langue($clickurl) . '" target="_Blank" >[ URL ]</a></p>';
                    }
    
                    echo '<form action="banners.php" method="get">';
    
                    if ($imageurl != '') {
                        echo '
                        <div class="mb-3 row">
                            <label class="control-label col-sm-12" for="url">' . __d('banners', 'Changer') . ' URL</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="url" maxlength="200" value="' . $clickurl . '" />
                            </div>
                        </div>';
                    } else {
                        echo '
                        <div class="mb-3 row">
                            <label class="control-label col-sm-12" for="url">' . __d('banners', 'Changer') . ' URL</label>
                            <div class="col-sm-12">
                                <input class="form-control" type="text" name="url" maxlength="200" value="' . htmlentities($clickurl, ENT_QUOTES, cur_charset) . '" />
                            </div>
                        </div>';
                    }
    
                    echo '
                    <input type="hidden" name="login" value="' . $login . '" />
                    <input type="hidden" name="bid" value="' . $bid . '" />
                    <input type="hidden" name="pass" value="' . $pass . '" />
                    <input type="hidden" name="cid" value="' . $cid . '" />
                    <input class="btn btn-primary" type="submit" name="op" value="' . __d('banners', 'Changer') . '" />
                    </form>
                    </p>
                    </div>';
                }
    
                // Finnished Banners
                echo "<br />";
                echo '
                <h3>' . __d('banners', 'Bannières terminées pour') . ' ' . $name . '</h3>
                <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-columns="true" data-icons="icons" data-icons-prefix="fa">
                    <thead>
                    <tr>
                        <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'ID') . '</td>
                        <th data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Impressions') . '</th>
                        <th data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Clics') . '</th>
                        <th class="n-t-col-xs-1" data-halign="center" data-align="right" data-sortable="true">% ' . __d('banners', 'Clics') . '</th>
                        <th data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Date de début') . '</th>
                        <th data-halign="center" data-align="right" data-sortable="true">' . __d('banners', 'Date de fin') . '</th>
                    </tr>
                    </thead>
                    <tbody>';
    
                $result = sql_query("SELECT bid, impressions, clicks, datestart, dateend FROM bannerfinish WHERE cid='$cid'");
    
                while (list($bid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result)) {
                    $percent = substr(100 * $clicks / $impressions, 0, 5);
    
                    echo '
                    <tr>
                        <td>' . $bid . '</td>
                        <td>' . wrh($impressions) . '</td>
                        <td>' . $clicks . '</td>
                        <td>' . $percent . ' %</td>
                        <td><small>' . $datestart . '</small></td>
                        <td><small>' . $dateend . '</small></td>
                    </tr>';
                }
    
                echo '
                    </tbody>
                </table>';
    
                Css::adminfoot('fv', '', '', 'no');
                
                footer_page();
            } else
                IncorrectLogin();
        }
    }
    
    function EmailStats($login, $cid, $bid)
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
    
    function change_banner_url_by_client($login, $pass, $cid, $bid, $url)
    {
        header_page();
    
        $result = sql_query("SELECT passwd FROM bannerclient WHERE cid='$cid'");
        list($passwd) = sql_fetch_row($result);
    
        if (!empty($pass) and $pass == $passwd) {
            sql_query("UPDATE banner SET clickurl='$url' WHERE bid='$bid'");
            echo '<div class="alert alert-success">' . __d('banners', 'Vous avez changé l\'url de la bannière') . '<br /><a href="javascript:history.go(-1)" class="alert-link">' . __d('banners', 'Retour en arrière') . '</a></div>';
        } else
            echo '<div class="alert alert-danger">' . __d('banners', 'Identifiant incorrect !') . '<br />' . __d('banners', 'Merci de') . ' <a href="banners.php?op=login" class="alert-link">' . __d('banners', 'vous reconnecter.') . '</a></div>';
    
        footer_page();
    }

}