<?php

namespace Modules\ReseauxSociaux\Controllers\Admin;

use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class ReseauxSociauxSaveSetReseaux extends AdminController
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
