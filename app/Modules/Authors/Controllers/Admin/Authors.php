<?php

namespace App\Modules\Authors\Controllers\Admin;

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;

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
    
}
