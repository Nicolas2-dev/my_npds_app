<?php

namespace App\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Admin extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'BannersAdmin';
        // $f_titre = __d('banners', 'Administration des bannières');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/banners.html";

        // switch ($op) {
        //     case 'BannersAdd':
        //         BannersAdd($name, $cid, $imptotal, $imageurl, $clickurl, $userlevel);
        //         break;
        
        //     case 'BannerAddClient':
        //         BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo);
        //         break;
        
        //     case 'BannerFinishDelete':
        //         BannerFinishDelete($bid);
        //         break;
        
        //     case 'BannerDelete':
        //         BannerDelete($bid, $ok);
        //         break;
        
        //     case 'BannerEdit':
        //         BannerEdit($bid);
        //         break;
        
        //     case 'BannerChange':
        //         BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $userlevel);
        //         break;
        
        //     case 'BannerClientDelete':
        //         BannerClientDelete($cid, $ok);
        //         break;
        
        //     case 'BannerClientEdit':
        //         BannerClientEdit($cid);
        //         break;
        
        //     case 'BannerClientChange':
        //         BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd);
        //         break;
                
        //     case 'BannersAdmin':
        //     default:
        //         BannersAdmin();
        //         break;
        // }        
    // }

    function BannersAdmin()
    {
        echo '
        <hr />
        <h3>' . __d('banners', 'Bannières actives') . '</h3>
        <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'ID') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center" >' . __d('banners', 'Nom de l\'annonceur') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Impressions') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Imp. restantes') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right">' . __d('banners', 'Clics') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >% ' . __d('banners', 'Clics') . '</th>
                    <th data-halign="center" data-align="center">' . __d('banners', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM banner WHERE userlevel!='9' ORDER BY bid");
        
        while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {
            
            $result2 = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
            list($cid, $name) = sql_fetch_row($result2);
    
            $percent = $impmade == 0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
            $left = $imptotal == 0 ? __d('banners', 'Illimité') : $imptotal - $impmade;
    
            //  | <span class="small"><a href="#" class="tooltip">'.basename(Language::aff_langue($imageurl)).'<em><img src="'.$imageurl.'" /></em></a></span>
            echo '
                <tr>
                    <td>' . $bid . '</td>
                    <td>' . $name . '</td>
                    <td>' . $impmade . '</td>
                    <td>' . $left . '</td>
                    <td>' . $clicks . '</td>
                    <td>' . $percent . '%</td>
                    <td><a href="admin.php?op=BannerEdit&amp;bid=' . $bid . '"><i class="fa fa-edit fa-lg me-3" title="' . __d('banners', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerDelete&amp;bid=' . $bid . '&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('banners', 'Effacer') . '" data-bs-toggle="tooltip"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        echo '
        <hr />
        <h3>' . __d('banners', 'Bannières inactives') . '</h3>
        <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'ID') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Impressions') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Imp. restantes') . '</th>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">' . __d('banners', 'Clics') . '</th>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">% ' . __d('banners', 'Clics') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right">' . __d('banners', 'Nom de l\'annonceur') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="center">' . __d('banners', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT bid, cid, imageurl, imptotal, impmade, clicks, date FROM banner WHERE userlevel='9' order by bid");
        
        while (list($bid, $cid, $imageurl, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result)) {

            $result2 = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
            list($cid, $name) = sql_fetch_row($result2);
    
            $percent = $impmade == 0 ? '0' : substr(100 * $clicks / $impmade, 0, 5);
            $left = $imptotal == 0 ? __d('banners', 'Illimité') : $imptotal - $impmade;
    
            echo '
                <tr>
                <td>' . $bid . '</td>
                <td>' . $impmade . '</td>
                <td>' . $left . '</td>
                <td>' . $clicks . '</td>
                <td>' . $percent . '%</td>
                <td>' . $name . ' | <span class="small">' . basename(Language::aff_langue($imageurl)) . '</span></td>
                <td><a href="admin.php?op=BannerEdit&amp;bid=' . $bid . '" ><i class="fa fa-edit fa-lg me-3" title="' . __d('banners', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerDelete&amp;bid=' . $bid . '&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('banners', 'Effacer') . '" data-bs-toggle="tooltip"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <hr />
        <h3>' . __d('banners', 'Bannières terminées') . '</h3>
        <table data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'ID') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Imp.') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Clics') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" > % ' . __d('banners', 'Clics') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center" >' . __d('banners', 'Date de début') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center" >' . __d('banners', 'Date de fin') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center">' . __d('banners', 'Nom de l\'annonceur') . '</th>
                    <th data-halign="center" data-align="center">' . __d('banners', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT bid, cid, impressions, clicks, datestart, dateend FROM bannerfinish ORDER BY bid");
        
        while (list($bid, $cid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result)) {

            $result2 = sql_query("SELECT cid, name FROM bannerclient WHERE cid='$cid'");
            list($cid, $name) = sql_fetch_row($result2);
    
            if ($impressions == 0) 
                $impressions = 1;
    
            $percent = substr(100 * $clicks / $impressions, 0, 5);
    
            echo '
                    <tr>
                        <td>' . $bid . '</td>
                        <td>' . $impressions . '</td>
                        <td>' . $clicks . '</td>
                        <td>' . $percent . '%</td>
                        <td>' . $datestart . '</td>
                        <td>' . $dateend . '</td>
                        <td>' . $name . '</td>
                        <td><a href="admin.php?op=BannerFinishDelete&amp;bid=' . $bid . '" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('banners', 'Effacer') . '" data-bs-toggle="tooltip"></i></a></td>
                    </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <hr />
        <h3>' . __d('banners', 'Annonceurs faisant de la publicité') . '</h3>
        <table id="tad_banannon" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-show-toggle="true" data-show-columns="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'ID') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center" >' . __d('banners', 'Nom de l\'annonceur') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="right" >' . __d('banners', 'Bannières actives') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center" >' . __d('banners', 'Nom du Contact') . '</th>
                    <th data-sortable="true" data-halign="center" >' . __d('banners', 'E-mail') . '</th>
                    <th data-halign="center" data-align="right" >' . __d('banners', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT cid, name, contact, email FROM bannerclient ORDER BY cid");
        
        while (list($cid, $name, $contact, $email) = sql_fetch_row($result)) {
            $result2 = sql_query("SELECT cid FROM banner WHERE cid='$cid'");
            $numrows = sql_num_rows($result2);
    
            echo '
                <tr>
                    <td>' . $cid . '</td>
                    <td>' . $name . '</td>
                    <td>' . $numrows . '</td>
                    <td>' . $contact . '</td>
                    <td>' . $email . '</td>
                    <td><a href="admin.php?op=BannerClientEdit&amp;cid=' . $cid . '"><i class="fa fa-edit fa-lg me-3" title="' . __d('banners', 'Editer') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=BannerClientDelete&amp;cid=' . $cid . '" class="text-danger"><i class="fas fa-trash fa-lg text-danger" title="' . __d('banners', 'Effacer') . '" data-bs-toggle="tooltip"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        // Add Banner
        $result = sql_query("SELECT * FROM bannerclient");
        $numrows = sql_num_rows($result);
    
        if ($numrows > 0) {
            echo '
            <hr />
            <h3 class="my-3">' . __d('banners', 'Ajouter une nouvelle bannière') . '</h3>
            <span class="help-block">' . __d('banners', 'Pour les bannières Javascript, saisir seulement le code javascript dans la zone URL du clic et laisser la zone image vide.') . '</span>
            <span class="help-block">' . __d('banners', 'Pour les bannières encore plus complexes (Flash, ...), saisir simplement la référence à votre_répertoire/votre_fichier .txt (fichier de code php) dans la zone URL du clic et laisser la zone image vide.') . '</span>
            <form id="bannersnewbanner" action="admin.php" method="post">
                <div class="form-floating mb-3">
                    <select class="form-select" name="cid">';
    
            $result = sql_query("SELECT cid, name FROM bannerclient");
    
            while (list($cid, $name) = sql_fetch_row($result)) {
                echo '<option value="' . $cid . '">' . $name . '</option>';
            }
    
            echo '
                    </select>
                    <label for="cid">' . __d('banners', 'Nom de l\'annonceur') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="number" id="imptotal" name="imptotal" min="0" max="99999999999" required="required" />
                    <label for="imptotal">' . __d('banners', 'Impressions réservées') . '</label>
                    <span class="help-block">0 = ' . __d('banners', 'Illimité') . '</span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="imageurl" name="imageurl" maxlength="320" />
                    <label for="imageurl">' . __d('banners', 'URL de l\'image') . '</label>
                    <span class="help-block text-end"><span id="countcar_imageurl"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="clickurl" name="clickurl" maxlength="320" required="required" />
                    <label for="clickurl">' . __d('banners', 'URL du clic') . '</label>
                    <span class="help-block text-end"><span id="countcar_clickurl"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="number" id="userlevel" name="userlevel" min="0" max="9" value="0" required="required" />
                    <label for="userlevel">' . __d('banners', 'Niveau de l\'Utilisateur') . '</label>
                    <span class="help-block">' . __d('banners', '0=Tout le monde, 1=Membre seulement, 3=Administrateur seulement, 9=Désactiver') . '.</span>
                </div>
                <input type="hidden" name="op" value="BannersAdd" />
                <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('banners', 'Ajouter une bannière') . ' </button>
            </form>';
        }
    
        // Add Client
        echo '
        <hr />
        <h3 class="my-3">' . __d('banners', 'Ajouter un nouvel Annonceur') . '</h3>
        <form id="bannersnewanno" action="admin.php" method="post">
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="name" name="name" maxlength="60" required="required" />
                <label for="name">' . __d('banners', 'Nom de l\'annonceur') . '</label>
                <span class="help-block text-end" id="countcar_name"></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="contact" name="contact" maxlength="60" required="required" />
                <label for="contact">' . __d('banners', 'Nom du Contact') . '</label>
                <span class="help-block text-end" id="countcar_contact"></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="email" id="email" name="email" maxlength="254" required="required" />
                <label for="email">' . __d('banners', 'E-mail') . '</label>
                <span class="help-block text-end" id="countcar_email"></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="login" name="login" maxlength="10" required="required" />
                <label for="login">' . __d('banners', 'Identifiant') . '</label>
                <span class="help-block text-end" id="countcar_login"></span>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="password" id="passwd" name="passwd" maxlength="20" required="required" />
                <label for="passwd">' . __d('banners', 'Mot de Passe') . '</label>
                <span class="help-block text-end" id="countcar_passwd"></span>
                <div class="progress" style="height: 0.4rem;">
                    <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="extrainfo" name="extrainfo" style="height:140px"></textarea>
                <label for="extrainfo">' . __d('banners', 'Informations supplémentaires') . '</label>
            </div>
            <input type="hidden" name="op" value="BannerAddClient" />
            <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('banners', 'Ajouter un annonceur') . '</button>
        </form>';
    
        $arg1 = $numrows > 0 ? 'var formulid = ["bannersnewbanner","bannersnewanno"];' : 'var formulid = ["bannersnewanno"];';
    
        $arg1 .= '
            inpandfieldlen("imageurl",320);
            inpandfieldlen("clickurl",320);
            inpandfieldlen("name",60);
            inpandfieldlen("contact",60);
            inpandfieldlen("email",254);
            inpandfieldlen("login",10);
            inpandfieldlen("passwd",20);';
    
        $fv_parametres = '
        passwd: {
            validators: {
                checkPassword: {},
            }
        },';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function BannersAdd($cid, $imptotal, $imageurl, $clickurl, $userlevel)
    {
        sql_query("INSERT INTO banner VALUES (NULL, '$cid', '$imptotal', '1', '0', '$imageurl', '$clickurl', '$userlevel', now())");
        
        Header("Location: admin.php?op=BannersAdmin");
    }
    
    function BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo)
    {
        sql_query("INSERT INTO bannerclient VALUES (NULL, '$name', '$contact', '$email', '$login', '$passwd', '$extrainfo')");
        
        Header("Location: admin.php?op=BannersAdmin");
    }
    
    function BannerFinishDelete($bid)
    {
        sql_query("DELETE FROM bannerfinish WHERE bid='$bid'");
    
        Header("Location: admin.php?op=BannersAdmin");
    }
    
    function BannerDelete($bid, $ok = 0)
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
    
    function BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl, $userlevel)
    {
        $imp = $imptotal + $impadded;

        sql_query("UPDATE banner SET cid='$cid', imptotal='$imp', imageurl='$imageurl', clickurl='$clickurl', userlevel='$userlevel' WHERE bid='$bid'");
        
        Header("Location: admin.php?op=BannersAdmin");
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
    
    function BannerClientEdit($cid)
    {
        $result = sql_query("SELECT name, contact, email, login, passwd, extrainfo FROM bannerclient WHERE cid='$cid'");
        list($name, $contact, $email, $login, $passwd, $extrainfo) = sql_fetch_row($result);
    
        echo '
            <hr />
            <h3 class="mb-3">' . __d('banners', 'Editer l\'annonceur') . '</h3>
            <form action="admin.php" method="post" id="bannersedanno">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="name" name="name" value="' . $name . '" maxlength="60" required="required" />
                    <label for="name">' . __d('banners', 'Nom de l\'annonceur') . '</label>
                    <span class="help-block text-end"><span id="countcar_name"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="contact" name="contact" value="' . $contact . '" maxlength="60" required="required" />
                    <label for="contact">' . __d('banners', 'Nom du Contact') . '</label>
                    <span class="help-block text-end"><span id="countcar_contact"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="email" id="email" name="email" maxlength="254" value="' . $email . '" required="required" />
                    <label for="email">' . __d('banners', 'E-mail') . '</label>
                    <span class="help-block text-end"><span id="countcar_email"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="login" name="login" maxlength="10" value="' . $login . '" required="required" />
                    <label for="login">' . __d('banners', 'Identifiant') . '</label>
                    <span class="help-block text-end"><span id="countcar_login"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" id="passwd" name="passwd" maxlength="20" value="' . $passwd . '" required="required" />
                    <label for="passwd">' . __d('banners', 'Mot de Passe') . '</label>
                    <span class="help-block text-end"><span id="countcar_passwd"></span></span>
                    <div class="progress" style="height: 0.4rem;">
                        <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="extrainfo" name="extrainfo" style="height:140px">' . $extrainfo . '</textarea>
                    <label for="extrainfo">' . __d('banners', 'Informations supplémentaires') . '</label>
                </div>
                <input type="hidden" name="cid" value="' . $cid . '" />
                <input type="hidden" name="op" value="BannerClientChange" />
                <input class="btn btn-primary my-3" type="submit" value="' . __d('banners', 'Modifier annonceur') . '" />
            </form>';
    
        $arg1 = '
            var formulid = ["bannersedanno"];
            inpandfieldlen("name",60);
            inpandfieldlen("contact",60);
            inpandfieldlen("email",254);
            inpandfieldlen("login",10);
            inpandfieldlen("passwd",20);
        ';
    
        $fv_parametres = '
        passwd: {
            validators: {
                checkPassword: {},
            }
        },';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd)
    {
        sql_query("UPDATE bannerclient SET name='$name', contact='$contact', email='$email', login='$login', passwd='$passwd', extrainfo='$extrainfo' WHERE cid='$cid'");
        
        Header("Location: admin.php?op=BannersAdmin");
    }

}
