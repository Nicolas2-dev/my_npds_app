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

    function BannerEdit($bid)
    {
        $result = sql_query("SELECT cid, imptotal, impmade, clicks, imageurl, clickurl, userlevel FROM banner WHERE bid='$bid'");
        list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $userlevel) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-2">' . __d('banners', 'Edition Bannière') . '</h3>';
    
        if ($imageurl != '')
            echo '<img class="img-fluid" src="' . Language::aff_langue($imageurl) . '" alt="banner" /><br />';
        else
            echo $clickurl;
    
        echo '
        <span class="help-block mt-2">' . __d('banners', 'Pour les bannières Javascript, saisir seulement le code javascript dans la zone URL du clic et laisser la zone image vide.') . '</span>
        <span class="help-block">' . __d('banners', 'Pour les bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone URL du clic et laisser la zone image vide.') . '</span>
        <form id="bannersadm" action="admin.php" method="post">
            <div class="form-floating mb-3">
                <select class="form-select" id="cid" name="cid">';
    
        $result = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
        list($cid, $name) = sql_fetch_row($result);
    
        echo '<option value="' . $cid . '" selected="selected">' . $name . '</option>';
    
        $result = sql_query("SELECT cid, name FROM bannerclient");
    
        while (list($ccid, $name) = sql_fetch_row($result)) {
            if ($cid != $ccid)
                echo '<option value="' . $ccid . '">' . $name . '</option>';
        }
    
        echo '
                </select>
                <label for="cid">' . __d('banners', 'Nom de l\'annonceur') . '</label>
            </div>';
    
        $impressions = $imptotal == 0 ? __d('banners', 'Illimité') : $imptotal;
    
        echo '
            <div class="form-floating mb-3">
                <input class="form-control" type="number" id="impadded" name="impadded" min="0" max="99999999999" required="required" value="' . $imptotal . '"/>
                <label for="impadded">' . __d('banners', 'Ajouter plus d\'affichages') . '</label>
                <span class="help-block">' . __d('banners', 'Réservé : ') . '<strong>' . $impressions . '</strong> ' . __d('banners', 'Fait : ') . '<strong>' . $impmade . '</strong></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="imageurl" name="imageurl" maxlength="320" value="' . $imageurl . '" />
                <label for="imageurl">' . __d('banners', 'URL de l\'image') . '</label>
                <span class="help-block text-end"><span id="countcar_imageurl"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="clickurl" name="clickurl" maxlength="320" value="' . htmlentities($clickurl, ENT_QUOTES, cur_charset) . '" />
                <label for="clickurl">' . __d('banners', 'URL du clic') . '</label>
                <span class="help-block text-end"><span id="countcar_clickurl"></span></span>
            </div>
            <div class="form-floating mb-3"> 
                <input class="form-control" type="number" name="userlevel" min="0" max="9" value="' . $userlevel . '" required="required" />
                <label for="userlevel">' . __d('banners', 'Niveau de l\'Utilisateur') . '</label>
                <span class="help-block">' . __d('banners', '0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver') . '.</span>
            </div>
            <input type="hidden" name="bid" value="' . $bid . '" />
            <input type="hidden" name="imptotal" value="' . $imptotal . '" />
            <input type="hidden" name="op" value="BannerChange" />
            <button class="btn btn-primary my-3" type="submit"><i class="fa fa-check-square fa-lg me-2"></i>' . __d('banners', 'Modifier la Bannière') . '</button>
        </form>';
    
        $arg1 = '
            var formulid = ["bannersadm"];
            inpandfieldlen("imageurl",320);
            inpandfieldlen("clickurl",320);
        ';
    
        Css::adminfoot('fv', '', $arg1, '');
    }

}
