<?php

namespace Modules\Forum\Controllers\Admin\Config;

use Modules\Npds\Core\AdminController;


class GeolocSave extends AdminController
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
    protected $hlpfile = 'geoloc';

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
    protected $f_meta_nom = 'geoloc';


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
        $this->f_titre = __d('geoloc', 'Configuration du module Geoloc');

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
     * @param [type] $api_key_bing
     * @param [type] $api_key_mapbox
     * @param [type] $ch_lat
     * @param [type] $ch_lon
     * @param [type] $cartyp
     * @param [type] $geo_ip
     * @param [type] $api_key_ipdata
     * @param [type] $key_lookup
     * @param [type] $co_unit
     * @param [type] $mark_typ
     * @param [type] $ch_img
     * @param [type] $nm_img_acg
     * @param [type] $nm_img_mbcg
     * @param [type] $nm_img_mbg
     * @param [type] $w_ico
     * @param [type] $h_ico
     * @param [type] $f_mbg
     * @param [type] $mbg_sc
     * @param [type] $mbg_t_ep
     * @param [type] $mbg_t_co
     * @param [type] $mbg_t_op
     * @param [type] $mbg_f_co
     * @param [type] $mbg_f_op
     * @param [type] $mbgc_sc
     * @param [type] $mbgc_t_ep
     * @param [type] $mbgc_t_co
     * @param [type] $mbgc_t_op
     * @param [type] $mbgc_f_co
     * @param [type] $mbgc_f_op
     * @param [type] $acg_sc
     * @param [type] $acg_t_ep
     * @param [type] $acg_t_co
     * @param [type] $acg_t_op
     * @param [type] $acg_f_co
     * @param [type] $acg_f_op
     * @param [type] $cartyp_b
     * @param [type] $img_mbgb
     * @param [type] $w_ico_b
     * @param [type] $h_ico_b
     * @param [type] $h_b
     * @param [type] $z_b
     * @param [type] $ModPath
     * @param [type] $ModStart
     * @return void
     */
    public function SaveSetgeoloc($api_key_bing, $api_key_mapbox, $ch_lat, $ch_lon, $cartyp, $geo_ip, $api_key_ipdata, $key_lookup, $co_unit, $mark_typ, $ch_img, $nm_img_acg, $nm_img_mbcg, $nm_img_mbg, $w_ico, $h_ico, $f_mbg, $mbg_sc, $mbg_t_ep, $mbg_t_co, $mbg_t_op, $mbg_f_co, $mbg_f_op, $mbgc_sc, $mbgc_t_ep, $mbgc_t_co, $mbgc_t_op, $mbgc_f_co, $mbgc_f_op, $acg_sc, $acg_t_ep, $acg_t_co, $acg_t_op, $acg_f_co, $acg_f_op, $cartyp_b, $img_mbgb, $w_ico_b, $h_ico_b, $h_b, $z_b, $ModPath, $ModStart)
    {
        //==> modifie le fichier de configuration
        $file_conf = fopen("modules/geoloc/geoloc.conf", "w+");
        $content = "<?php \n";
        $content .= "/************************************************************************/\n";
        $content .= "/* DUNE by App                                                         */\n";
        $content .= "/* ===========================                                          */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* App Copyright (c) 2002-" . date('Y') . " by Philippe Brunier                     */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
        $content .= "/* it under the terms of the GNU General Public License as published by */\n";
        $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* module geoloc version 4.1                                            */\n";
        $content .= "/* geoloc.conf file 2008-" . date('Y') . " by Jean Pierre Barbary (jpb)              */\n";
        $content .= "/* dev team : Philippe Revilliod (Phr), A.NICOL                         */\n";
        $content .= "/************************************************************************/\n";
        $content .= "\$api_key_bing = \"$api_key_bing\"; // clef api bing maps \n";
        $content .= "\$api_key_mapbox = \"$api_key_mapbox\"; // clef api mapbox \n";
        $content .= "\$ch_lat = \"$ch_lat\"; // Champ lat dans sql \n";
        $content .= "\$ch_lon = \"$ch_lon\"; // Champ long dans sql \n";
        $content .= "// interface carte \n";
        $content .= "\$cartyp = \"$cartyp\"; // Type de carte \n";
        $content .= "\$co_unit = \"$co_unit\"; // Coordinates Units\n";
        $content .= "\$ch_img = \"$ch_img\"; // Chemin des images \n";
        $content .= "\$geo_ip = $geo_ip; // Autorisation de géolocalisation des IP \n";
        $content .= "\$api_key_ipdata = \"$api_key_ipdata\"; // Clef API pour provider IP ipdata \n";
        $content .= "\$key_lookup = \"$key_lookup\"; // Clef API pour provider IP extreme-ip-lookup \n";
        $content .= "\$nm_img_acg = \"$nm_img_acg\"; // Nom fichier image anonyme géoréférencé en ligne \n";
        $content .= "\$nm_img_mbcg = \"$nm_img_mbcg\"; // Nom fichier image membre géoréférencé en ligne \n";
        $content .= "\$nm_img_mbg = \"$nm_img_mbg\"; // Nom fichier image membre géoréférencé \n";
        $content .= "\$mark_typ = $mark_typ; // Type de marker \n";
        $content .= "\$w_ico = \"$w_ico\"; // Largeur icone des markers \n";
        $content .= "\$h_ico = \"$h_ico\"; // Hauteur icone des markers\n";
        $content .= "\$f_mbg = \"$f_mbg\"; // Font SVG \n";
        $content .= "\$mbg_sc = \"$mbg_sc\"; // Echelle du Font SVG du membre \n";
        $content .= "\$mbg_t_ep = \"$mbg_t_ep\"; // Epaisseur trait Font SVG du membre \n";
        $content .= "\$mbg_t_co = \"$mbg_t_co\"; // Couleur trait SVG du membre \n";
        $content .= "\$mbg_t_op = \"$mbg_t_op\"; // Opacité trait SVG du membre \n";
        $content .= "\$mbg_f_co = \"$mbg_f_co\"; // Couleur fond SVG du membre \n";
        $content .= "\$mbg_f_op = \"$mbg_f_op\"; // Opacité fond SVG du membre \n";
        $content .= "\$mbgc_sc = \"$mbgc_sc\"; // Echelle du Font SVG du membre géoréférencé \n";
        $content .= "\$mbgc_t_ep = \"$mbgc_t_ep\"; // Epaisseur trait Font SVG du membre géoréférencé \n";
        $content .= "\$mbgc_t_co = \"$mbgc_t_co\"; // Couleur trait SVG du membre géoréférencé \n";
        $content .= "\$mbgc_t_op = \"$mbgc_t_op\"; // Opacité trait SVG du membre géoréférencé \n";
        $content .= "\$mbgc_f_co = \"$mbgc_f_co\"; // Couleur fond SVG du membre géoréférencé \n";
        $content .= "\$mbgc_f_op = \"$mbgc_f_op\"; // Opacité fond SVG du membre géoréférencé \n";
        $content .= "\$acg_sc = \"$acg_sc\"; // Echelle du Font SVG pour anonyme en ligne \n";
        $content .= "\$acg_t_ep = \"$acg_t_ep\"; // Epaisseur trait Font SVG pour anonyme en ligne \n";
        $content .= "\$acg_t_co = \"$acg_t_co\"; // Couleur trait SVG pour anonyme en ligne \n";
        $content .= "\$acg_t_op = \"$acg_t_op\"; // Opacité trait SVG pour anonyme en ligne \n";
        $content .= "\$acg_f_co = \"$acg_f_co\"; // Couleur fond SVG pour anonyme en ligne \n";
        $content .= "\$acg_f_op = \"$acg_f_op\"; // Opacité fond SVG pour anonyme en ligne \n";
        $content .= "// interface bloc \n";
        $content .= "\$cartyp_b = \"$cartyp_b\"; // Type de carte pour le bloc \n";
        $content .= "\$img_mbgb = \"$img_mbgb\"; // Nom fichier image membre géoréférencé pour le bloc \n";
        $content .= "\$w_ico_b = \"$w_ico_b\"; // Largeur icone marker dans le bloc \n";
        $content .= "\$h_ico_b = \"$h_ico_b\"; // Hauteur icone marker dans le bloc\n";
        $content .= "\$h_b = \"$h_b\"; // hauteur carte dans bloc\n";
        $content .= "\$z_b = \"$z_b\"; // facteur zoom carte dans bloc\n";
        $content .= "?>";

        fwrite($file_conf, $content);
        fclose($file_conf);
        //<== modifie le fichier de configuration
    }

}
