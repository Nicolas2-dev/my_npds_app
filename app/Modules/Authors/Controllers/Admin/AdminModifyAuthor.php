<?php

namespace App\Modules\Authors\Controllers\Admin;

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;

class AdminModifyAuthor extends AdminController
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
    
}
