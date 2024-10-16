<?php

namespace Modules\ReseauxSociaux\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class ReseauxSociauxEditReseaux extends AdminController
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
    protected $hlpfile = 'social';

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
    protected $f_meta_nom = 'reseaux-sociaux';


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
        $this->f_titre = __d('reseauxsociaux', 'Module') . ' : ' . $this->module;

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
     * case "AddReseaux":
     * case "EditReseaux":
     *     EditReseaux($f_meta_nom, $f_titre, $rs_id, $rs_url, $rs_ico, $subop, $old_id);
     * 
     * Undocumented function
     *
     * @param [type] $f_meta_nom
     * @param [type] $f_titre
     * @param [type] $rs_id
     * @param [type] $rs_url
     * @param [type] $rs_ico
     * @param [type] $subop
     * @param [type] $old_id
     * @return void
     */
    public function EditReseaux($f_meta_nom, $f_titre, $rs_id, $rs_url, $rs_ico, $subop, $old_id)
    {
        if (file_exists("modules/ReseauxSociaux/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/reseaux-sociaux.conf.php");
        }

        if ($subop == 'AddReseaux') {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('reseauxsociaux', 'Ajouter') . '</h3>';
        } else {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('reseauxsociaux', 'Editer') . '</h3>';
        }

        echo '
        <form id="reseauxadm" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-3" for="rs_id">' . __d('reseauxsociaux', 'Nom') . '</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" id="rs_id" name="rs_id"  maxlength="50"  placeholder="' . __d('reseauxsociaux', '') . '" value="' . urldecode($rs_id) . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_rs_id"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-3" for="rs_url">' . __d('reseauxsociaux', 'URL') . '</label>
                <div class="col-sm-9">
                    <input class="form-control" type="url" id="rs_url" name="rs_url"  maxlength="100" placeholder="' . __d('reseauxsociaux', '') . '" value="' . urldecode($rs_url) . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_rs_url"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-3" for="rs_ico">' . __d('reseauxsociaux', 'Icône') . '</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" id="rs_ico" name="rs_ico"  maxlength="40" placeholder="' . __d('reseauxsociaux', '') . '" value="' . stripcslashes(urldecode($rs_ico)) . '" required="required" />
                    <span class="help-block text-end"><span id="countcar_rs_ico"></span></span>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-9 ms-sm-auto">
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;' . __d('reseauxsociaux', 'Sauver') . '</button>
                    <input type="hidden" name="op" value="Extend-Admin-SubModule" />
                    <input type="hidden" name="subop" value="SaveSetReseaux" />
                    <input type="hidden" name="adm_img_mod" value="1" />
                    <input type="hidden" name="old_id" value="' . urldecode($rs_id) . '" />
                </div>
            </div>
        </form>';

        $arg1 = '
            var formulid = ["reseauxadm"];
            inpandfieldlen("rs_id",50);
            inpandfieldlen("rs_url",100);
            inpandfieldlen("rs_ico",40);';

        Css::adminfoot('fv', '', $arg1, '');
    }

}
