<?php

namespace Modules\ReseauxSociaux\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class ReseauxSociaux extends AdminController
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
     * Undocumented function
     *
     * @param [type] $f_meta_nom
     * @param [type] $f_titre
     * @return void
     */
    public function ListReseaux($f_meta_nom, $f_titre)
    {
        if (file_exists("modules/ReseauxSociaux/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/reseaux-sociaux.conf.php");
        }

        echo '
        <hr />
        <h3><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=AddReseaux"><i class="fa fa-plus-square"></i></a>&nbsp;' . __d('reseauxsociaux', 'Ajouter') . '</h3>
        <table id ="lst_rs_adm" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
            <thead>
                <tr>
                    <th class="n-t-col-xs-3" data-sortable="true" data-halign="center" data-align="right">' . __d('reseauxsociaux', 'Nom') . '</th>
                    <th class="n-t-col-xs-5" data-sortable="true" data-halign="center">' . __d('reseauxsociaux', 'URL') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="center">' . __d('reseauxsociaux', 'Icône') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('reseauxsociaux', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($rs as $v1) {
            echo '
                <tr>
                    <td>' . $v1[0] . '</td>
                    <td>' . $v1[1] . '</td>
                    <td><i class="fab fa-' . $v1[2] . ' fa-2x text-muted align-middle"></i></td>
                    <td>
                    <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=EditReseaux&amp;rs_id=' . urlencode($v1[0]) . '&amp;rs_url=' . urlencode($v1[1]) . '&amp;rs_ico=' . urlencode($v1[2]) . '" ><i class="fa fa-edit fa-lg me-2 align-middle" title="' . __d('reseauxsociaux', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a>
                    <a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;subop=DeleteReseaux&amp;rs_id=' . urlencode($v1[0]) . '&amp;rs_url=' . urlencode($v1[1]) . '&amp;rs_ico=' . urlencode($v1[2]) . '" ><i class="fas fa-trash fa-lg text-danger align-middle" title="' . __d('reseauxsociaux', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                </tr>';
        }

        echo '
            </tbody>
        </table>';

        Css::adminfoot('', '', '', '');
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

    /**
     * case "SaveSetReseaux":
     * case "DeleteReseaux":
     *    SaveSetReseaux($rs_id, $rs_url, $rs_ico, $subop, $old_id);
     *    ListReseaux($f_meta_nom, $f_titre);
     * 
     * Undocumented function
     *
     * @param [type] $rs_id
     * @param [type] $rs_url
     * @param [type] $rs_ico
     * @param [type] $subop
     * @param [type] $old_id
     * @return void
     */
    public function SaveSetReseaux($rs_id, $rs_url, $rs_ico, $subop, $old_id)
    {
        if (file_exists("modules/ReseauxSociaux/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/reseaux-sociaux.conf.php");
        }

        $newar = array($rs_id, $rs_url, $rs_ico);
        $newrs = array();

        $j = 0;

        foreach ($rs as $v1) {
            if (in_array($old_id, $v1, true)) {
                unset($rs[$j]);
            }

            $j++;
        }

        foreach ($rs as $v1) {
            if (!in_array($rs_id, $v1, true)) {
                $newrs[] = $v1;
            }
        }

        if ($subop !== 'DeleteReseaux') {
            $newrs[] = $newar;
        }

        $file = fopen("modules/ReseauxSociaux/reseaux-sociaux.conf.php", "w+");

        $content = "<?php \n";
        $content .= "/************************************************************************/\n";
        $content .= "/* DUNE by App                                                         */\n";
        $content .= "/* ===========================                                          */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* Reseaux-sociaux Add-On ... ver. 1.0                                  */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* App Copyright (c) 2002-" . date('Y') . " by Philippe Brunier         */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
        $content .= "/* it under the terms of the GNU General Public License as published by */\n";
        $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* reseaux-sociaux                                                      */\n";
        $content .= "/* reseaux-sociaux_conf 2016 by jpb                                     */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* version 1.0 17/02/2016                                               */\n";
        $content .= "/************************************************************************/\n";
        $content .= "// Do not change if you dont know what you do ;-)\n";
        $content .= "// \$rs=[['rs name','rs url',rs class fontawesome for rs icon],[...]]\n";
        $content .= "\$rs = [\n";
        $li = '';

        foreach ($newrs as $v1) {
            $li .= '[\'' . $v1[0] . '\',\'' . $v1[1] . '\',\'' . $v1[2] . '\'],' . "\n";
        }

        $li = substr_replace($li, '', -2, 1);

        $content .= $li;
        $content .= "];\n";
        $content .= "?>";

        fwrite($file, $content);
        fclose($file);
        @chmod("modules/ReseauxSociaux/reseaux-sociaux.conf.php", 0666);
    }

}
