<?php

namespace App\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Banners extends AdminController
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

    function BannerClientDelete($cid, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM banner WHERE cid='$cid'");
            sql_query("DELETE FROM bannerclient WHERE cid='$cid'");
    
            Header("Location: admin.php?op=BannersAdmin");
        } else {
            $result = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
            list($cid, $name) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="text-danger">' . __d('banners', 'Supprimer l\'Annonceur') . '</h3>';
    
            echo '
            <div class="alert alert-secondary my-3">' . __d('banners', 'Vous êtes sur le point de supprimer cet annonceur : ') . ' <strong>' . $name . '</strong> ' . __d('banners', 'et toutes ses bannières !!!');
            
            $result2 = sql_query("SELECT imageurl, clickurl FROM banner WHERE cid='$cid'");
            $numrows = sql_num_rows($result2);
    
            if ($numrows == 0)
                echo '<br />' . __d('banners', 'Cet annonceur n\'a pas de bannière active pour le moment.') . '</div>';
            else
                echo '<br /><span class="text-danger"><b>' . __d('banners', 'ATTENTION !!!') . '</b></span><br />' . __d('banners', 'Cet annonceur a les BANNIERES ACTIVES suivantes dans') . ' ' . Config::get('npds.sitename') . '</div>';
            
            while (list($imageurl, $clickurl) = sql_fetch_row($result2)) {
                echo $imageurl != '' 
                    ?'<img class="img-fluid" src="' . Language::aff_langue($imageurl) . '" alt="" /><br />' 
                    :$clickurl . '<br />';
            }
        }
    
        echo '<div class="alert alert-danger mt-3">' . __d('banners', 'Etes-vous sûr de vouloir effacer cet annonceur et TOUTES ses bannières ?') . '</div>
        <a href="admin.php?op=BannerClientDelete&amp;cid=' . $cid . '&amp;ok=1" class="btn btn-danger">' . __d('banners', 'Oui') . '</a> <a href="admin.php?op=BannersAdmin" class="btn btn-secondary">' . __d('banners', 'Non') . '</a>';
        
        Css::adminfoot('', '', '', '');
    }

}
