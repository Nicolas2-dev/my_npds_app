<?php

namespace App\Modules\Authors\Controllers\Admin;

use Npds\Config\Config;
use App\Modules\Authors\Models\Author;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Auth;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Support\Facades\Mailer;
use App\Modules\Npds\Support\Facades\Password;
use App\Modules\Authors\Support\Facades\Author as L_Author;


class AdminAuthors extends AdminController
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
    protected $hlpfile = "authors";

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
    protected $f_meta_nom = 'mod_authors';


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
        $this->f_titre = __d('authors', 'Administrateurs');

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

        // switch ($op) {
        //     case 'mod_authors':
        //         displayadmins();
        //         break;
        
        //     case 'modifyadmin':
        //         modifyadmin($chng_aid);
        //         break;
        
        //     case 'UpdateAuthor':
        //         settype($chng_radminsuper, 'int');
        //         settype($ad_d_27, 'int');
        
        //         updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $ad_d_27, $old_pwd);
        //         break;
        
        //     case 'AddAuthor':
        //         settype($add_radminsuper, 'int');
        
        //         if (!($add_aid && $add_name && $add_email && $add_pwd)) {
        //             echo L_author::error_handler(__d('authors', 'Vous devez remplir tous les Champs') . '<br />');
        //         }
        
        //         include_once('functions.php');
        
        //         if (checkdnsmail($add_email) === false) {
        //             echo L_Author::error_handler(__d('authors', 'ERREUR : DNS ou serveur de mail incorrect') . '<br />');
        //         }
        
        //         $AlgoCrypt = PASSWORD_BCRYPT;
        //         $min_ms = 100;
        //         $options = ['cost' => Password::getOptimalBcryptCostParameter($add_pwd, $AlgoCrypt, $min_ms)];
        //         $hashpass = password_hash($add_pwd, $AlgoCrypt, $options);
        //         $add_pwdX = crypt($add_pwd, $hashpass);
        
        //         $result = sql_query("INSERT INTO authors VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwdX', '1', '0', '$add_radminsuper')");
        //         Author::updatedroits($add_aid);
        
        //         // Copie du fichier pour filemanager
        //         if ($add_radminsuper or isset($ad_d_27)) // $ad_d_27 pas là ?
        //             @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($add_aid) . ".conf.php");
        
        //         global $aid;
        //         Ecr_Log('security', "AddAuthor($add_aid) by AID : $aid", '');
        
        //         Header("Location: admin.php?op=mod_authors");
        //         break;
        
        //     case 'deladmin':
        //         echo '
        //         <hr />
        //         <h3>' . __d('authors', 'Effacer l\'Administrateur') . ' : <span class="text-muted">' . $del_aid . '</span></h3>
        //         <div class="alert alert-danger">
        //         <p><strong>' . __d('authors', 'Etes-vous sûr de vouloir effacer') . ' ' . $del_aid . ' ? </strong></p>
        //         <a href="admin.php?op=deladminconf&amp;del_aid=' . $del_aid . '" class="btn btn-danger btn-sm">' . __d('authors', 'Oui') . '</a>
        //         &nbsp;<a href="admin.php?op=mod_authors" class="btn btn-secondary btn-sm">' . __d('authors', 'Non') . '</a>
        //         </div>';
        
        //         adminfoot('', '', '', '');
        //         break;
        

        //     case 'deladminconf':
        //         sql_query("DELETE FROM authors WHERE aid='$del_aid'");
        //         Author::deletedroits($chng_aid = $del_aid);
        
        //         sql_query("DELETE FROM publisujet WHERE aid='$del_aid'");
        
        //         // Supression du fichier pour filemanager
        //         @unlink("modules/f-manager/users/" . strtolower($del_aid) . ".conf.php");
        
        //         global $aid;
        //         Ecr_Log('security', "DeleteAuthor($del_aid) by AID : $aid", '');
                
        //         Header("Location: admin.php?op=mod_authors");
        //         break;
        // }

    //}

    /**
     * [displayadmins description]
     *
     * @return  [type]  [return description]
     */
    public function displayadmins()
    {
        global $listdroits, $listdroitsmodulo, $scri_check;
    
        $result = sql_query("SELECT aid, name, url, email, radminsuper FROM authors");
    
        $displayadmins = '
        <hr />
        <h3>' . __d('authors', 'Les administrateurs') . '</h3>
        <table id="tab_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th data-sortable="true" data-halign="center">' . __d('authors', 'Nom') . '</th>
                    <th data-sortable="true" data-halign="center">' . __d('authors', 'E-mail') . '</th>
                    <th data-halign="center" data-align="right">' . __d('authors', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($a_aid, $name, $url, $email, $supadm) = sql_fetch_row($result)) {
            if ($supadm == 1) 
                $displayadmins .=  '<tr class="table-danger">';
            else 
                $displayadmins .=  '<tr>';
    
            $displayadmins .=  '
                <td>' . $a_aid . '</td>
                <td>' . $email . '</td>
                <td align="right" nowrap="nowrap">
                   <a href="admin.php?op=modifyadmin&amp;chng_aid=' . $a_aid . '" class=""><i class="fa fa-edit fa-lg" title="' . __d('authors', 'Modifier l\'information') . '" data-bs-toggle="tooltip"></i></a>&nbsp;
                   <a href="mailto:' . $email . '"><i class="fa fa-at fa-lg" title="' . __d('authors', 'Envoyer un courriel à') . ' ' . $a_aid . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
            
            if ($url != '')
                $displayadmins .=   '<a href="' . $url . '"><i class="fas fa-external-link-alt fa-lg" title="' . __d('authors', 'Visiter le site web') . '" data-bs-toggle="tooltip"></i></a>&nbsp;';
            
            $displayadmins .=   '
                    <a href="admin.php?op=deladmin&amp;del_aid=' . $a_aid . '" ><i class="fas fa-trash fa-lg text-danger" title="' . __d('authors', 'Effacer l\'Auteur') . '" data-bs-toggle="tooltip" ></i></a>
                    </td>
                </tr>';
        }
    
        $displayadmins .=   '
            </tbody>
        </table>
        <hr />
        <h3>' . __d('authors', 'Nouvel administrateur') . '</h3>
        <form id="nou_adm" action="admin.php" method="post">
            <fieldset>
                <legend><img src="' . site_url(Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext')) . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Informations') . '" /> ' . __d('authors', 'Informations') . ' </legend>
                <div class="form-floating mb-3 mt-3">
                    <input id="add_aid" class="form-control" type="text" name="add_aid" maxlength="30" placeholder="' . __d('authors', 'Surnom') . '" required="required" />
                    <label for="add_aid">' . __d('authors', 'Surnom') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_add_aid"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="add_name" class="form-control" type="text" name="add_name" maxlength="50" placeholder="' . __d('authors', 'Nom') . '" required="required" />
                    <label for="add_name">' . __d('authors', 'Nom') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_add_name"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="add_email" class="form-control" type="email" name="add_email" maxlength="254" placeholder="' . __d('authors', 'E-mail') . '" required="required" />
                    <label for="add_email">' . __d('authors', 'E-mail') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_add_email"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="add_url" class="form-control" type="url" name="add_url" maxlength="320" placeholder="' . __d('authors', 'URL') . '" />
                    <label for="add_url">' . __d('authors', 'URL') . '</label>
                    <span class="help-block text-end"><span id="countcar_add_url"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="add_pwd" class="form-control" type="password" name="add_pwd" maxlength="20" placeholder="' . __d('authors', 'Mot de Passe') . '" required="required" />
                    <label for="add_pwd">' . __d('authors', 'Mot de Passe') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end" id="countcar_add_pwd"></span>
                    <div class="progress mt-2" style="height: 0.4rem;">
                    <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                    <input id="cb_radminsuper" class="form-check-input" type="checkbox" name="add_radminsuper" value="1" />
                    <label class="form-check-label text-danger" for="cb_radminsuper">' . __d('authors', 'Super administrateur') . '</label>
                    </div>
                    <span class="help-block">' . __d('authors', 'Si Super administrateur est coché, cet administrateur aura TOUS les droits.') . '</span>
                </div>
            </fieldset>
            <fieldset>
                <legend><img src="' . site_url(Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext')) . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Droits') . '" /> ' . __d('authors', 'Droits') . ' </legend>
                <div id="adm_droi_f" class="container-fluid ">
                    <div class="mb-3">
                    <input type="checkbox" id="ckball_f" />&nbsp;<span class="small text-muted" id="ckb_status_f">' . __d('authors', 'Tout cocher') . '</span>
                    </div>
                    <div class="row">
                    ' . $listdroits . '
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><img src="' . site_url(Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext')) . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Droits modules') . '" /> ' . __d('authors', 'Droits modules') . ' </legend>
                <div id="adm_droi_m" class="container-fluid">
                    <div class="mb-3">
                    <input type="checkbox" id="ckball_m" />&nbsp;<span class="small text-muted" id="ckb_status_m">' . __d('authors', 'Tout cocher') . '</span>
                    </div>
                    <div class="row">
                    ' . $listdroitsmodulo . '
                    </div>
                </div>
                <button class="btn btn-primary my-3" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('authors', 'Ajouter un administrateur') . '</button>
                </div>
                <input type="hidden" name="op" value="AddAuthor" />
            </fieldset>
        </form>
        ' . $scri_check;
    
        $arg1 = '
            var formulid = ["nou_adm"];
            ' . auto_complete('admin', 'aid', 'authors', '', '0') . '
            ' . auto_complete('adminname', 'name', 'authors', '', '0') . '
            inpandfieldlen("add_aid",30);
            inpandfieldlen("add_name",50);
            inpandfieldlen("add_email",254);
            inpandfieldlen("add_url",320);
            inpandfieldlen("add_pwd",20);
            ';
    
        $fv_parametres = '
        add_aid: {
            validators: {
                callback: {
                    message: "' . __d('authors', 'Ce surnom n\'est pas disponible') . '",
                    callback: function(input) {
                    if($.inArray(btoa(input.value), admin) !== -1)
                        return false;
                    else
                        return true;
                    }
                }
            }
        },
        add_name: {
            validators: {
                callback: {
                    message: "' . __d('authors', 'Ce nom n\'est pas disponible') . '",
                    callback: function(input) {
                    if($.inArray(btoa(input.value), adminname) !== -1)
                        return false;
                    else
                        return true;
                    }
                }
            }
        },
        add_pwd: {
            validators: {
                checkPassword: {},
            }
        },';
    
        $displayadmins .=   Css::adminfoot('fv', $fv_parametres, $arg1, '');

        $this->title($this->f_titre);

        $this->set('displayadmins', $displayadmins);
    }
    
    /**
     * [modifyadmin description]
     *
     * @param   [type]  $chng_aid  [$chng_aid description]
     *
     * @return  [type]             [return description]
     */
    public function modifyadmin($chng_aid)
    {
        global $scri_check, $fv_parametres;

        echo '
        <hr />
        <h3>' . __d('authors', 'Actualiser l\'administrateur') . ' : <span class="text-muted">' . $chng_aid . '</span></h3>';
    
        $result = sql_query("SELECT aid, name, url, email, pwd, radminsuper FROM authors WHERE aid='$chng_aid'");
        list($chng_aid, $chng_name, $chng_url, $chng_email, $chng_pwd, $chng_radminsuper) = sql_fetch_row($result);
    
        $supadm_inp = $chng_radminsuper == 1 ? ' checked="checked"' : '';
    
        //==> construction des check-box des droits
        $listdroits = '';
        $listdroitsmodulo = '';
    
        $result3    = sql_query("SELECT * FROM droits WHERE d_aut_aid ='$chng_aid'");
        $datas      = array();
    
        while ($data = sql_fetch_row($result3)) {
            $datas[] = $data[1];
        }
    
        $R = sql_query("SELECT fid, fnom, fnom_affich, fcategorie FROM fonctions f WHERE f.finterface =1 AND fcategorie < 7 ORDER BY f.fcategorie");
        
        while (list($fid, $fnom, $fnom_affich, $fcategorie) = sql_fetch_row($R)) {
            $chec = in_array($fid, $datas) ? 'checked="checked"' : '';
    
            if ($fcategorie == 6) {
                $listdroitsmodulo .= '
                <div class="col-md-4 col-sm-6">
                    <div class="form-check">
                    <input class="ckbm form-check-input" id="ad_d_m_' . $fnom . '" type="checkbox" ' . $chec . ' name="ad_d_m_' . $fnom . '" value="' . $fid . '" />
                    <label class="form-check-label" for="ad_d_m_' . $fnom . '">' . $fnom_affich . '</label>
                    </div>
                </div>';
            } else {
                if ($fid != 12)
                    $listdroits .= '
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check">
                        <input class="ckbf form-check-input" id="ad_d_' . $fid . '" type="checkbox" ' . $chec . ' name="ad_d_' . $fid . '" value="' . $fid . '" />
                        <label class="form-check-label" for="ad_d_' . $fid . '">' . __d('authors', $fnom_affich) . '</label>
                        </div>
                    </div>';
            }
        }
    
        //<== construction des check-box des droits
        echo '
        <form id="mod_adm" class="" action="admin.php" method="post">
            <fieldset>
                <legend><img src="' . Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext') . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Informations') . '" title="' . $chng_aid . '" /> ' . __d('authors', 'Informations') . '</legend>
                <div class="form-floating mb-3 mt-3">
                    <input id="chng_name" class="form-control" type="text" name="chng_name" value="' . $chng_name . '" maxlength="30" placeholder="' . __d('authors', 'Nom') . '" required="required" />
                    <label for="chng_name">' . __d('authors', 'Nom') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_chng_name"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="chng_email" class="form-control" type="text" name="chng_email" value="' . $chng_email . '" maxlength="254" placeholder="' . __d('authors', 'E-mail') . '" required="required" />
                    <label for="chng_email">' . __d('authors', 'E-mail') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_chng_email"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="chng_url" class="form-control" type="url" name="chng_url" value="' . $chng_url . '" maxlength="320" placeholder="' . __d('authors', 'URL') . '" />
                    <label for="chng_url">' . __d('authors', 'URL') . '</label>
                    <span class="help-block text-end"><span id="countcar_chng_url"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="chng_pwd" class="form-control" type="password" name="chng_pwd" maxlength="20" placeholder="' . __d('authors', 'Mot de Passe') . '" title="' . __d('authors', 'Entrez votre nouveau Mot de Passe') . '" />
                    <label for="chng_pwd">' . __d('authors', 'Mot de Passe') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end" id="countcar_chng_pwd"></span>
                    <div class="progress" style="height: 0.4rem;">
                    <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input id="chng_pwd2" class="form-control" type="password" name="chng_pwd2" maxlength="20" placeholder="' . __d('authors', 'Mot de Passe') . '" title="' . __d('authors', 'Entrez votre nouveau Mot de Passe') . '" />
                    <label for="chng_pwd2">' . __d('authors', 'Mot de Passe') . ' <span class="text-danger">*</span></label>
                    <span class="help-block text-end"><span id="countcar_chng_pwd2"></span></span>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                    <input id="cb_radminsuper" class="form-check-input" type="checkbox" name="chng_radminsuper" value="1" ' . $supadm_inp . ' />
                    <label class="form-check-label text-danger" for="cb_radminsuper">' . __d('authors', 'Super administrateur') . '</label>
                    </div>
                    <span class="help-block">' . __d('authors', 'Si Super administrateur est coché, cet administrateur aura TOUS les droits.') . '</span>
                </div>
                <input type="hidden" name="chng_aid" value="' . $chng_aid . '" />
            </fieldset>
            <fieldset>
                <legend><img src="' . Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext') . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Droits') . '" /> ' . __d('authors', 'Droits') . ' </legend>
                <div id="adm_droi_f" class="container-fluid ">
                    <div class="mb-3">
                    <input type="checkbox" id="ckball_f" />&nbsp;<span class="small text-muted" id="ckb_status_f">' . __d('authors', 'Tout cocher') . '</span>
                    </div>
                    <div class="row">
                    ' . $listdroits . '
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><img src="' . Config::get('npds.adminimg') . 'authors.' . Config::get('npds.admf_ext') . '" class="vam" border="0" width="24" height="24" alt="' . __d('authors', 'Droits modules') . '" /> ' . __d('authors', 'Droits modules') . ' </legend>
                <div id="adm_droi_m" class="container-fluid ">
                    <div class="mb-3">
                    <input type="checkbox" id="ckball_m" />&nbsp;<span class="small text-muted" id="ckb_status_m">' . __d('authors', 'Tout cocher') . '</span>
                    </div>
                    <div class="row">
                    ' . $listdroitsmodulo . '
                    </div>
                </div>
                <input type="hidden" name="old_pwd" value="' . $chng_pwd . '" />
                <input type="hidden" name="op" value="UpdateAuthor" />
                <button class="btn btn-primary my-3" type="submit"><i class="fa fa-check fa-lg me-2"></i>' . __d('authors', 'Actualiser l\'administrateur') . '</button>
            </fieldset>
        </form>';
    
        echo $scri_check;
    
        $arg1 = '
            var formulid = ["mod_adm"]
                inpandfieldlen("chng_name",50);
                inpandfieldlen("chng_email",254);
                inpandfieldlen("chng_url",320);
                inpandfieldlen("chng_pwd",20);
                inpandfieldlen("chng_pwd2",20);
            ';
    
        $fv_parametres = '
        chng_pwd: {
            validators: {
                checkPassword: {},
            }
        },
        chng_pwd2: {
            validators: {
                identical: {
                    compare: function() {
                    return mod_adm.querySelector(\'[name="chng_pwd"]\').value;
                    },
                }
            }
        },
        !###!
        mod_adm.querySelector(\'[name="chng_pwd"]\').addEventListener("input", function() {
            fvitem.revalidateField("chng_pwd2");
        });';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    /**
     * [updateadmin description]
     *
     * @param   [type]  $chng_aid          [$chng_aid description]
     * @param   [type]  $chng_name         [$chng_name description]
     * @param   [type]  $chng_email        [$chng_email description]
     * @param   [type]  $chng_url          [$chng_url description]
     * @param   [type]  $chng_radminsuper  [$chng_radminsuper description]
     * @param   [type]  $chng_pwd          [$chng_pwd description]
     * @param   [type]  $chng_pwd2         [$chng_pwd2 description]
     * @param   [type]  $ad_d_27           [$ad_d_27 description]
     * @param   [type]  $old_pwd           [$old_pwd description]
     *
     * @return  [type]                     [return description]
     */
    public function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminsuper, $chng_pwd, $chng_pwd2, $ad_d_27, $old_pwd)
    {
        if (!($chng_aid && $chng_name && $chng_email))
            Header("Location: admin.php?op=mod_authors");
    
        if (Mailer::checkdnsmail($chng_email) === false) {
            echo L_Author::error_handler(__d('authors', 'ERREUR : DNS ou serveur de mail incorrect') . '<br />');
        }
    
        $result = sql_query("SELECT radminsuper FROM authors WHERE aid='$chng_aid'");
        list($ori_radminsuper) = sql_fetch_row($result);
    
        if (!$ori_radminsuper and $chng_radminsuper) {
            @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
            Author::deletedroits($chng_aid);
        }
    
        if ($ori_radminsuper and !$chng_radminsuper) {
            @unlink("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
            Author::updatedroits($chng_aid);
        }
    
        if (file_exists("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php") and $ad_d_27 != '27')
            @unlink("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
    
        if (($chng_radminsuper or $ad_d_27 != '') and !file_exists("modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php")) {
            @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($chng_aid) . ".conf.php");
        }
    
        if ($chng_pwd2 != '') {
            if ($chng_pwd != $chng_pwd2) {
                echo L_Author::error_handler(__d('authors', 'Désolé, les nouveaux Mots de Passe ne correspondent pas. Cliquez sur retour et recommencez') . '<br />');
            }
    
            $AlgoCrypt = PASSWORD_BCRYPT;
            $min_ms = 100;
            $options = ['cost' => Password::getOptimalBcryptCostParameter($chng_pwd, $AlgoCrypt, $min_ms)];
            $hashpass = password_hash($chng_pwd, $AlgoCrypt, $options);
            $chng_pwd = crypt($chng_pwd, $hashpass);
    
            if ($old_pwd) {
    
                $admin = Auth::chech('admin');

                $Xadmin = base64_decode($admin);
                $Xadmin = explode(':', $Xadmin);
                $aid    = urlencode($Xadmin[0]);

                $AIpwd = $Xadmin[1];
    
                if ($aid == $chng_aid) {
                    if (md5($old_pwd) == $AIpwd and $chng_pwd != '') {
                        $admin = base64_encode("$aid:" . md5($chng_pwd));
    
                        $admin_cook_duration = Config::get('npds.admin_cook_duration');

                        if ($admin_cook_duration <= 0) {
                            $admin_cook_duration = 1; 
                        }
    
                        $timeX = time() + (3600 * $admin_cook_duration);
    
                        Cookie::set('admin', $admin, $timeX);
                        Cookie::set('adm_exp', $timeX, $timeX);
                    }
                }
            }
    
            $result = $chng_radminsuper == 1 ?
                sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'") :
                sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0', pwd='$chng_pwd', hashkey='1' WHERE aid='$chng_aid'");
        } else {
            if ($chng_radminsuper == 1) {
                $result = sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='$chng_radminsuper' WHERE aid='$chng_aid'");
                
                Author::deletedroits($chng_aid);
            } else {
                $result = sql_query("UPDATE authors SET name='$chng_name', email='$chng_email', url='$chng_url', radminsuper='0' WHERE aid='$chng_aid'");
                
                Author::deletedroits($chng_aid);
                Author::updatedroits($chng_aid);
            }
        }
    
        global $aid;
        Ecr_Log('security', "ModifyAuthor($chng_name) by AID : $aid", '');
    
        Header("Location: admin.php?op=mod_authors");
    }
    
}
