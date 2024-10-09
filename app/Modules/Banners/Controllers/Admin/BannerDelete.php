<?php

namespace App\Modules\Banners\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class BannerDelete extends AdminController
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
    protected $hlpfile = 'banners';

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
    protected $f_meta_nom = 'BannersAdmin';


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
        $this->f_titre = __d('banners', 'Administration des bannières');

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
     * @param [type] $bid
     * @param integer $ok
     * @return void
     */
    public function BannerDelete($bid, $ok = 0)
    {
        // global $f_meta_nom, $f_titre;
    
        if ($ok == 1) {
            sql_query("DELETE FROM banner WHERE bid='$bid'");
    
            Header("Location: admin.php?op=BannersAdmin");
        } else {

            $result = sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl FROM banner WHERE bid='$bid'");
            list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="text-danger">' . __d('banners', 'Effacer Bannière') . '</h3>';
    
            echo $imageurl != '' 
                ?'<a href="' . Language::aff_langue($clickurl) . '"><img class="img-fluid" src="' . Language::aff_langue($imageurl) . '" alt="banner" /></a><br />' 
                :$clickurl;
    
            echo '
            <table data-toggle="table" data-mobile-responsive="true">
                <thead>
                    <tr>
                    <th data-halign="center" data-align="right">' . __d('banners', 'ID') . '</th>
                    <th data-halign="center" data-align="right">' . __d('banners', 'Impressions') . '</th>
                    <th data-halign="center" data-align="right">' . __d('banners', 'Clics') . '</th>
                    <th data-halign="center" data-align="right">% ' . __d('banners', 'Clics') . '</th>
                    <th data-halign="center" data-align="center">' . __d('banners', 'Nom de l\'annonceur') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
            $result2 = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
            list($cid, $name) = sql_fetch_row($result2);
    
            $percent = substr(100 * $clicks / $impmade, 0, 5);
            $left = $imptotal == 0 ? __d('banners', 'Illimité') : $imptotal - $impmade;
    
            echo '
                    <tr>
                    <td>' . $bid . '</td>
                    <td>' . $impmade . '</td>
                    <td>' . $left . '</td>
                    <td>' . $clicks . '</td>
                    <td>' . $percent . '%</td>
                    <td>' . $name . '</td>
                    </tr>';
        }
    
        echo '
                </tbody>
            </table>
            <br />
            <div class="alert alert-danger">' . __d('banners', 'Etes-vous sûr de vouloir effacer cette Bannière ?') . '<br />
            <a class="btn btn-danger btn-sm mt-3" href="admin.php?op=BannerDelete&amp;bid=' . $bid . '&amp;ok=1">' . __d('banners', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm mt-3" href="admin.php?op=BannersAdmin" >' . __d('banners', 'Non') . '</a></div>';
        
        Css::adminfoot('', '', '', '');
    }

}
