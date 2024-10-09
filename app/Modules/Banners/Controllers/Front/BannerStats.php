<?php

namespace App\Modules\Banners\Controllers\Front;

use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class BannerStats extends FrontController
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
     * @param [type] $pass
     * @return void
     */
    public function bannerstats($login, $pass)
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

}
