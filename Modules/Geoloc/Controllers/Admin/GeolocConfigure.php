<?php

namespace Modules\Forum\Controllers\Admin\Config;

use Modules\Npds\Core\AdminController;


class GeolocConfigure extends AdminController
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
     * @param [type] $subop
     * @param [type] $ModPath
     * @param [type] $ModStart
     * @param [type] $ch_lat
     * @param [type] $ch_lon
     * @param [type] $cartyp
     * @param [type] $geo_ip
     * @param [type] $api_key_ipdata
     * @param [type] $key_lookup
     * @return void
     */
    public function Configuregeoloc($subop, $ModPath, $ModStart, $ch_lat, $ch_lon, $cartyp, $geo_ip, $api_key_ipdata, $key_lookup)
    {
        global $hlpfile, $f_meta_nom, $f_titre, $subop;

        include('modules/' . $ModPath . '/geoloc.conf');

        $hlpfile = 'modules/' . $ModPath . '/doc/aide_admgeo_' . Config::get('npds.language') . '.html';

        $result = sql_query("SELECT CONCAT(ROUND(((DATA_LENGTH + INDEX_LENGTH - DATA_FREE) / 1024 / 1024), 2), ' Mo') AS TailleMo FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'Config::get('npds.dbname')' AND TABLE_NAME = 'ip_loc'");
        $row = sql_fetch_array($result);

        $ar_fields = array('C3', 'C4', 'C5', 'C6', 'C7', 'C8');

        foreach ($ar_fields as $k => $v) {
            $req = '';
            $req = sql_query("SELECT $v FROM users_extend WHERE $v !=''");

            if (!sql_num_rows($req))
                $dispofield[] = $v;
        }

        GraphicAdmin($hlpfile);
        adminhead($f_meta_nom, $f_titre);

        $fonts_svg = array(
            ['user', 'uf007', 'Utilisateur'],
            ['userCircle', 'uf2bd', 'Utilisateur en cercle'],
            ['users', 'uf0c0', 'Utilisateurs'],
            ['heart', 'uf004', 'Coeur'],
            ['thumbtack', 'uf08d', 'Punaise'],
            ['circle', 'uf111', 'Cercle'],
            ['camera', 'uf030', 'Appareil photo'],
            ['anchor', 'uf13d', 'Ancre'],
            ['mapMarker', 'uf041', 'Marqueur carte'],
            ['plane', 'uf072', 'Avion'],
            ['star', 'uf005', 'Etoile'],
            ['home', 'uf015', 'Maison'],
            ['flag', 'uf024', 'Drapeau'],
            ['crosshairs', 'uf05b', 'Croix'],
            ['asterisk', 'uf069', 'Astérisque'],
            ['fire', 'uf06d', 'Flamme'],
            ['comment', 'uf075', 'Commentaire']
        );

        $fond_provider = array(
            ['OSM', __d('geoloc', 'Plan') . ' (OpenStreetMap)'],
            ['natural-earth-hypso-bathy', __d('geoloc', 'Relief') . ' (mapbox)'],
            ['geography-class', __d('geoloc', 'Carte') . ' (mapbox)'],
            ['Road', __d('geoloc', 'Plan') . ' (Bing maps)'],
            ['Aerial', __d('geoloc', 'Satellite') . ' (Bing maps)'],
            ['AerialWithLabels', __d('geoloc', 'Satellite') . ' et label (Bing maps)'],
            ['sat-google', __d('geoloc', 'Satellite') . ' (Google maps)'],
            ['World_Imagery', __d('geoloc', 'Satellite') . ' (ESRI)'],
            ['World_Shaded_Relief', __d('geoloc', 'Relief') . ' (ESRI)'],
            ['World_Physical_Map', __d('geoloc', 'Physique') . ' (ESRI)'],
            ['World_Topo_Map', __d('geoloc', 'Topo') . ' (ESRI)']
        );

        if ($api_key_bing == '' and $api_key_mapbox == '')
            unset($fond_provider[1], $fond_provider[2], $fond_provider[3], $fond_provider[4], $fond_provider[5]);
        elseif ($api_key_bing == '')
            unset($fond_provider[3], $fond_provider[4], $fond_provider[5]);
        elseif ($api_key_mapbox == '')
            unset($fond_provider[1], $fond_provider[2]);

        echo '
        <hr />
        <a href="modules.php?ModPath=geoloc&amp;ModStart=geoloc"><i class="fa fa-globe fa-lg me-2 "></i>' . __d('geoloc', 'Carte') . '</a>
        <form id="geolocset" name="geoloc_set" action="admin.php" method="post">
            <h4 class="my-3">' . __d('geoloc', 'Paramètres système') . '</h4>
            <fieldset id="para_sys" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
                <span class="text-danger">* ' . __d('geoloc', 'requis') . '</span>
                <div class="mb-3 row ">
                    <label class="col-form-label col-sm-6" for="ch_lat">' . __d('geoloc', 'Champ de table pour latitude') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                    <select class="form-select" name="ch_lat" id="ch_lat">
                        <option selected="selected">' . $ch_lat . '</option>';

        foreach ($dispofield as $ke => $va) {
            echo '<option>' . $va . '</option>';
        }

        echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row ">
                    <label class="col-form-label col-sm-6" for="ch_lon">' . __d('geoloc', 'Champ de table pour longitude') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                    <select class="form-select" name="ch_lon" id="ch_lon">
                        <option selected="selected">' . $ch_lon . '</option>';

        foreach ($dispofield as $ke => $va) {
            echo '<option>' . $va . '</option>';
        }

        echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="ch_img">' . __d('geoloc', 'Chemin des images') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" name="ch_img" id="ch_img" placeholder="Chemin des images" value="' . $ch_img . '" required="required" />
                    </div>
                </div>';

        $cky_geo = '';
        $ckn_geo = '';

        if ($geo_ip == 1)
            $cky_geo = 'checked="checked"';
        else
            $ckn_geo = 'checked="checked"';

        echo '
                <div class="mb-3 row">
                    <label class="col-sm-6 col-form-label" for="geo_ip">' . __d('geoloc', 'Géolocalisation des IP') . '</label>
                    <div class="col-sm-6 my-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="geo_oui" name="geo_ip" value="1" ' . $cky_geo . ' />
                        <label class="form-check-label" for="geo_oui">' . __d('geoloc', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="geo_no" name="geo_ip" value="0" ' . $ckn_geo . ' />
                        <label class="form-check-label" for="geo_no">' . __d('geoloc', 'Non') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="api_key_ipdata">' . __d('geoloc', 'Clef d\'API') . ' Ipdata</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="api_key_ipdata" id="api_key_ipdata" placeholder="" value="' . $api_key_ipdata . '" />
                    <span class="help-block small muted">' . $api_key_ipdata . '</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="key_lookup">' . __d('geoloc', 'Clef d\'API') . ' extreme-ip-lookup</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="key_lookup" id="key_lookup" placeholder="" value="' . $key_lookup . '" />
                    <span class="help-block small muted">' . $key_lookup . '</span>
                    <span class="help-block small muted"><a href="https://extreme-ip-lookup.com">https://extreme-ip-lookup.com</a></span>
                    </div>
                </div>
                <div class="mb-3 border border-light alert-secondary">
                    <div class="w-100 p-2"><span class="col-form-label">' . __d('geoloc', 'Taille de la table') . '<code> ip_loc </code><b>' . $row['TailleMo'] . '</b></span> <span class="float-end"><a href="admin.php?op=Extend-Admin-SubModule&amp;ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;subop=vidip" title="' . __d('geoloc', 'Vider la table des IP géoréférencées') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fas fa-trash fa-lg text-danger"></i></a></span></div>
                </div>
            </fieldset>
            <hr />
            <h4 class="my-3" >' . __d('geoloc', 'Interface carte') . '</h4>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="api_key_bing">' . __d('geoloc', 'Clef d\'API') . ' Bing maps</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="api_key_bing" id="api_key_bing" placeholder="" value="' . $api_key_bing . '" />
                    <span class="help-block small muted">' . $api_key_bing . '</span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="api_key_mapbox">' . __d('geoloc', 'Clef d\'API') . ' Mapbox</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="api_key_mapbox" id="api_key_mapbox" placeholder="" value="' . $api_key_mapbox . '" />
                    <span class="help-block small muted">' . $api_key_mapbox . '</span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-8">
                    <fieldset id="para_car" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
                    <div class="mb-3 row ">
                        <label class="col-form-label col-sm-6" for="cartyp">' . __d('geoloc', 'Type de carte') . '<span class="text-danger ms-1">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-select" name="cartyp" id="cartyp">';

        foreach ($fond_provider as $k => $v) {
            $sel = $v[0] == $cartyp ? 'selected="selected"' : '';

            switch ($k) {
                case '0':
                    echo '<optgroup label="OpenStreetMap">';
                    break;

                case '1':
                    echo '<optgroup label="Mapbox">';
                    break;

                case '4':
                    echo '<optgroup label="Bing maps">';
                    break;

                case '6':
                    echo '<optgroup label="Google">';
                    break;

                case '7':
                    echo '<optgroup label="ESRI">';
                    break;
            }

            echo '<option ' . $sel . ' value="' . $v[0] . '">' . $v[1] . '</option>';

            switch ($k) {
                case '0':
                case '2':
                case '5':
                case '6':
                case '10':
                    echo '</optgroup>';
                    break;
            }
        }

        echo '
                            </select>
                        </div>
                    </div>';

        $s_dd = '';
        $s_dm = '';

        if ($co_unit == 'dd')
            $s_dd = 'selected="selected"';
        else if ($co_unit == 'dms')
            $s_dm = 'selected="selected"';

        echo '
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-6" for="co_unit">' . __d('geoloc', 'Unité des coordonnées') . '<span class="text-danger ms-1">*</span></label>
                        <div class="col-sm-6">
                            <select class="form-select" name="co_unit" id="co_unit">
                                <option ' . $s_dd . '>dd</option>
                                <option ' . $s_dm . '>dms</option>
                            </select>
                        </div>
                    </div>';

        $cky_mar = '';
        $ckn_mar = '';

        if ($mark_typ == 1)
            $cky_mar = 'checked="checked"';
        else
            $ckn_mar = 'checked="checked"';

        echo '
                    <div class="mb-3 row">
                        <label class="col-sm-12 col-form-label" for="mark_typ">' . __d('geoloc', 'Type de marqueur') . '</label>
                        <div class="col-sm-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="img_img" name="mark_typ" value="1" ' . $cky_mar . ' checked="checked" />
                            <label class="form-check-label" for="img_img">' . __d('geoloc', 'Marqueur images de type png, gif, jpeg.') . '</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="img_svg" name="mark_typ" value="0" ' . $ckn_mar . ' />
                            <label class="form-check-label" for="img_svg">' . __d('geoloc', 'Marqueur SVG font ou objet vectoriel.') . '</label>
                        </div>
                    </div>
                    </div>
                </fieldset>
                <fieldset id="para_ima" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="nm_img_mbg">' . __d('geoloc', 'Image membre géoréférencé') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <span id="v_img_mbg" class="input-group-text"><img width="22" height="22" src="' . $ch_img . $nm_img_mbg . '" alt="' . __d('geoloc', 'Image membre géoréférencé') . '" /></span>
                            <input type="text" class="form-control input-lg" name="nm_img_mbg" id="nm_img_mbg" placeholder="' . __d('geoloc', 'Nom du fichier image') . '" value="' . $nm_img_mbg . '" required="required" />
                        </div>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="nm_img_mbcg">' . __d('geoloc', 'Image membre géoréférencé en ligne') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                        <div class="input-group ">
                            <span id="v_img_mbcg" class="input-group-text"><img width="22" height="22" src="' . $ch_img . $nm_img_mbcg . '" alt="' . __d('geoloc', 'Image membre géoréférencé en ligne') . '" /></span>
                            <input type="text" class="form-control input-lg" name="nm_img_mbcg" id="nm_img_mbcg" placeholder="' . __d('geoloc', 'Nom du fichier image') . '" value="' . $nm_img_mbcg . '" required="required" />
                        </div>
                    </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-6" for="nm_img_acg">' . __d('geoloc', 'Image anonyme géoréférencé en ligne') . '<span class="text-danger ms-1">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span id="v_img_acg" class="input-group-text"><img width="22" height="22" src="' . $ch_img . $nm_img_acg . '" alt="' . __d('geoloc', 'Image anonyme géoréférencé en ligne') . '" /></span>
                                <input type="text" class="form-control input-lg" name="nm_img_acg" id="nm_img_acg" placeholder="' . __d('geoloc', 'Nom du fichier image') . '" value="' . $nm_img_acg . '" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-6" for="w_ico">' . __d('geoloc', 'Largeur icône des marqueurs') . '<span class="text-danger ms-1">*</span></label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" name="w_ico" id="w_ico" maxlength="3" placeholder="Largeur des images" value="' . $w_ico . '" required="required" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="h_ico">' . __d('geoloc', 'Hauteur icône des marqueurs') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control" name="h_ico" id="h_ico" maxlength="3" placeholder="Hauteur des images" value="' . $h_ico . '" required="required" />
                    </div>
                    </div>
                </fieldset>
                <fieldset id="para_svg" class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
                    <div class="mb-3 row">
                    <label class="col-form-label col-sm-6" for="f_mbg">' . __d('geoloc', 'Marqueur font SVG') . '<span class="text-danger ms-1">*</span></label>
                    <div class="col-sm-6">
                        <div class="input-group">';

        $fafont = '';

        foreach ($fonts_svg as $v) {
            if ($v[0] == $f_mbg) $fafont = '&#x' . substr($v[1], 1) . ';';
        }

        echo '
                    <span id="vis_ic" class="input-group-text"><span class="fa fa-lg" id="fontchoice">' . $fafont . '</span></span>
                    <select class="form-select input-lg" name="f_mbg" id="f_mbg">';

        foreach ($fonts_svg as $v) {
            if ($v[0] == $f_mbg)
                $sel = 'selected="selected"';
            else
                $sel = '';

            echo '<option ' . $sel . ' value="' . $v[0] . '">' . $v[2] . '</option>';
        }

        echo '
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="mb-3 row">
                    <div class="col-4">
                        <div><span id="f_choice_mbg" class="fa fa-2x align-middle" style="color:' . $mbg_f_co . ';" >' . $fafont . '</span>&nbsp;<span>' . __d('geoloc', 'Membre') . '</span></div>
                    </div>
                    <div class="col-4">
                        <div><i id="f_choice_mbgc" class="fa fa-2x align-middle" style="color:' . $mbgc_f_co . ';" >' . $fafont . '</i>&nbsp;<span>' . __d('geoloc', 'Membre en ligne') . '</span></div>
                    </div>
                    <div class="col-4">
                        <div><i id="f_choice_acg" class="fa fa-2x align-middle" style="color:' . $acg_f_co . ';" >' . $fafont . '</i>&nbsp;<span>' . __d('geoloc', 'Anonyme en ligne') . '</span></div>
                    </div>
                    </div>
                    <div class="row g-2">
                    <div class="col-4 bkmbg">
                        <label class="col-form-label" for="mbg_f_co">' . __d('geoloc', 'Couleur fond') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="mbg_f_co" id="mbg_f_co" placeholder="' . __d('geoloc', 'Couleur du fond') . '" value="' . $mbg_f_co . '" />
                    </div>
                    <div class="col-4">
                        <label class="col-form-label" for="mbgc_f_co">' . __d('geoloc', 'Couleur fond') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="mbgc_f_co" id="mbgc_f_co" placeholder="' . __d('geoloc', 'Couleur du fond') . '" value="' . $mbgc_f_co . '" />
                    </div>
                    <div class="col-4">
                        <label class="col-form-label" for="acg_f_co">' . __d('geoloc', 'Couleur fond') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="acg_f_co" id="acg_f_co" placeholder="' . __d('geoloc', 'Couleur du fond') . '" value="' . $acg_f_co . '" />
                        </div>
                    </div>
                    <div class="row g-2">
                    <div class="col-4 bkmbg">
                        <label class="col-form-label" for="mbg_t_co">' . __d('geoloc', 'Couleur du trait') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="mbg_t_co" id="mbg_t_co" placeholder="' . __d('geoloc', 'Couleur du trait') . '" value="' . $mbg_t_co . '" />
                    </div>
                    <div class="col-4">
                        <label class="col-form-label" for="mbgc_t_co">' . __d('geoloc', 'Couleur du trait') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="mbgc_t_co" id="mbgc_t_co" placeholder="' . __d('geoloc', 'Couleur du trait') . '" value="' . $mbgc_t_co . '" />
                    </div>
                    <div class="col-4" >
                        <label class="col-form-label" for="acg_t_co">' . __d('geoloc', 'Couleur du trait') . '</label>
                        <input data-jscolor="{}" type="text" class="form-control form-control-sm" name="acg_t_co" id="acg_t_co" placeholder="' . __d('geoloc', 'Couleur du trait') . '" value="' . $acg_t_co . '" />
                    </div>
                    </div>
                    <div class="row g-2 mt-3">
                    <div class="col-4 bkmbg">
                        <div class="form-floating mb-1">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="mbg_f_op" id="mbg_f_op" value="' . $mbg_f_op . '" required="required" />
                            <label for="mbg_f_op">' . __d('geoloc', 'Opacité du fond') . '</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-floating mb-1">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="mbgc_f_op" id="mbgc_f_op" value="' . $mbgc_f_op . '" required="required" />
                            <label for="mbgc_f_op">' . __d('geoloc', 'Opacité du fond') . '</label>
                        </div>
                    </div>
                    <div class="col-4" >
                        <div class="form-floating mb-1">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="acg_f_op" id="acg_f_op" value="' . $acg_f_op . '" required="required" />
                            <label for="acg_f_op">' . __d('geoloc', 'Opacité du fond') . '</label>
                        </div>
                    </div>
                    </div>
                    <div class="row g-2 mt-3">
                    <div class="col-4 bkmbg">
                        <div class="form-floating">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="mbg_t_op" id="mbg_t_op" value="' . $mbg_t_op . '" required="required" />
                            <label for="mbg_t_op">' . __d('geoloc', 'Opacité du trait') . '</label>
                        </div>
                    </div>
                        <div class="col-4">
                        <div class="form-floating">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="mbgc_t_op" id="mbgc_t_op" value="' . $mbgc_t_op . '" required="required" />
                            <label for="mbgc_t_op">' . __d('geoloc', 'Opacité du trait') . '</label>
                        </div>
                    </div>
                    <div class="col-4" >
                        <div class="form-floating">
                            <input type="number" step="any" min="0" max="1" class="form-control" name="acg_t_op" id="acg_t_op" value="' . $acg_t_op . '" required="required" />
                            <label  for="acg_t_op">' . __d('geoloc', 'Opacité du trait') . '</label>
                        </div>
                    </div>
                    </div>
                    <div class="row g-2 mt-3">
                    <div class="col-4 bkmbg">
                        <div class="form-floating">
                            <input type="number" step="any" min="0" class="form-control" name="mbg_t_ep" id="mbg_t_ep" value="' . $mbg_t_ep . '" required="required" />
                            <label for="mbg_t_ep">' . __d('geoloc', 'Epaisseur du trait') . '</label>
                        </div>
                    </div>
                    <div class="col-4" >
                        <div class="form-floating">
                            <input type="number" step="any" min="0" class="form-control" name="mbgc_t_ep" id="mbgc_t_ep" value="' . $mbgc_t_ep . '" required="required" />
                            <label for="mbgc_t_ep">' . __d('geoloc', 'Epaisseur du trait') . '</label>
                        </div>
                    </div>
                    <div class="col-4" >
                        <div class="form-floating">
                            <input type="number" step="any" min="0" class="form-control" name="acg_t_ep" id="acg_t_ep" value="' . $acg_t_ep . '" required="required" />
                            <label for="acg_t_ep">' . __d('geoloc', 'Epaisseur du trait') . '</label>
                        </div>
                    </div>
                    </div>
                    <div class="row g-2 mt-2">
                    <div class="col-4 bkmbg">
                        <div class="form-floating">
                            <select class="form-select" name="mbg_sc" id="mbg_sc">
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>14</option>
                                <option>16</option>
                                <option>18</option>
                                <option>20</option>
                                <option>22</option>
                                <option>24</option>
                                <option>26</option>
                                <option>28</option>
                                <option>30</option>
                                <option>32</option>
                                <option>36</option>
                                <option>38</option>
                                <option>40</option>
                                <option selected="selected">' . $mbg_sc . '</option>
                            </select>
                            <label for="mbg_sc">' . __d('geoloc', 'Echelle') . '</label>
                        </div>
                        </div>
                        <div class="col-4">
                        <div class="form-floating">
                            <select class="form-select" name="mbgc_sc" id="mbgc_sc">
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>14</option>
                                <option>16</option>
                                <option>18</option>
                                <option>20</option>
                                <option>22</option>
                                <option>24</option>
                                <option>26</option>
                                <option>28</option>
                                <option>30</option>
                                <option>32</option>
                                <option>36</option>
                                <option>38</option>
                                <option>40</option>
                                <option selected="selected">' . $mbgc_sc . '</option>
                            </select>
                            <label for="mbgc_sc">' . __d('geoloc', 'Echelle') . '</label>
                        </div>
                    </div>
                    <div class="col-4" >
                        <div class="form-floating">
                            <select class="form-select" name="acg_sc" id="acg_sc">
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>14</option>
                                <option>16</option>
                                <option>18</option>
                                <option>20</option>
                                <option>22</option>
                                <option>24</option>
                                <option>26</option>
                                <option>28</option>
                                <option>30</option>
                                <option>32</option>
                                <option>36</option>
                                <option>38</option>
                                <option>40</option>
                                <option selected="selected">' . $acg_sc . '</option>
                            </select>
                            <label for="acg_sc">' . __d('geoloc', 'Echelle') . '</label>
                        </div>
                    </div>
                    </div>
                </fieldset>
        <hr />
        <h4 class="my-3">' . __d('geoloc', 'Interface bloc') . '</h4>
        <fieldset class="" style="padding-top: 16px; padding-right: 3px; padding-bottom: 6px;padding-left: 3px;">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="cartyp_b">' . __d('geoloc', 'Type de carte') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <select class="form-select" name="cartyp_b" id="cartyp_b">';

        foreach ($fond_provider as $k => $v) {

            $sel = $v[0] == $cartyp_b ? 'selected="selected"' : '';

            switch ($k) {
                case '0':
                    echo '<optgroup label="OpenStreetMap">';
                    break;

                case '1':
                    echo '<optgroup label="Mapbox">';
                    break;

                case '4':
                    echo '<optgroup label="Bing maps">';
                    break;

                case '6':
                    echo '<optgroup label="Google">';
                    break;

                case '7':
                    echo '<optgroup label="ESRI">';
                    break;
            }

            echo '<option ' . $sel . ' value="' . $v[0] . '">' . $v[1] . '</option>';

            switch ($k) {
                case '0':
                case '2':
                case '5':
                case '6':
                case '10':
                    echo '</optgroup>';
                    break;
            }
        }
        echo '
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="img_mbgb">' . __d('geoloc', 'Image membre géoréférencé') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <div class="input-group">
                    <span id="v_img_mbgb" class="input-group-text"><img src="' . $ch_img . $img_mbgb . '" /></span>
                    <input type="text" class="form-control" name="img_mbgb" id="img_mbgb" placeholder="Nom du fichier image" value="' . $img_mbgb . '" required="required" />
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="w_ico_b">' . __d('geoloc', 'Largeur icône des marqueurs') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="w_ico_b" id="w_ico_b" placeholder="Chemin des images" value="' . $w_ico_b . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="h_ico_b">' . __d('geoloc', 'Hauteur icône des marqueurs') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="h_ico_b" id="h_ico_b" placeholder="Chemin des images" value="' . $h_ico_b . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="h_b">' . __d('geoloc', 'Hauteur de la carte dans le bloc') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="h_b" id="h_b" placeholder="' . __d('geoloc', 'Hauteur de la carte dans le bloc') . '" value="' . $h_b . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="z_b">' . __d('geoloc', 'Zoom') . '<span class="text-danger ms-1">*</span></label>
                <div class="col-sm-6">
                    <select class="form-select" name="z_b" id="z_b">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                    <option>11</option>
                    <option>12</option>
                    <option>13</option>
                    <option>14</option>
                    <option>15</option>
                    <option>16</option>
                    <option>17</option>
                    <option>18</option>
                    <option>19</option>
                    <option selected="selected">' . $z_b . '</option>
                    </select>
                </div>
            </div>
            </fieldset>
            <div class="mb-3 row">
                <div class="col-sm-6 ms-sm-auto">
                    <button type="submit" class="btn btn-primary">' . __d('geoloc', 'Sauver') . '</button>
                </div>
            </div>
            <input type="hidden" name="op" value="Extend-Admin-SubModule" />
            <input type="hidden" name="ModPath" value="' . $ModPath . '" />
            <input type="hidden" name="ModStart" value="' . $ModStart . '" />
            <input type="hidden" name="subop" value="SaveSetgeoloc" />
            <input type="hidden" name="svg_path" value="" />
        </form>
        </div>
        <div class="col-sm-4">
        <div id="map_conf"></div>
            Icônes en service
        </div>
        </div>';

        $source_fond = '';

        switch ($cartyp) {
            case 'OSM':
                $source_fond = 'new ol.source.OSM()';
                break;

            case 'sat-google':
                $source_fond = ' new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"})';
                break;

            case 'Road':
            case 'Aerial':
            case 'AerialWithLabels':
                $source_fond = 'new ol.source.BingMaps({key: "' . $api_key_bing . '",imagerySet: "' . $cartyp . '"})';
                break;

            case 'natural-earth-hypso-bathy':
            case 'geography-class':
                $source_fond = ' new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox.' . $cartyp_b . '.json?access_token=' . $api_key_mapbox . '"})';
                break;

            case 'terrain':
            case 'toner':
            case 'watercolor':
                $source_fond = 'new ol.source.Stamen({layer:"' . $cartyp . '"})';
                break;

            case 'World_Imagery':
            case 'World_Shaded_Relief':
            case 'World_Physical_Map':
            case 'World_Topo_Map':
                $source_fond = 'new ol.source.XYZ({
                    attributions: ["Powered by Esri", "Source: Esri, DigitalGlobe, GeoEye, Earthstar Geographics, CNES/Airbus DS, USDA, USGS, AeroGRID, IGN, and the GIS User Community"],
                    attributionsCollapsible: true,
                    url: "https://services.arcgisonline.com/ArcGIS/rest/services/' . $cartyp . '/MapServer/tile/{z}/{y}/{x}",
                    maxZoom: 23
                })';

                /*
                $max_r='40000';
                $min_r='0';
                */

                $layer_id = $cartyp;
                break;

            default:
                $source_fond = 'new ol.source.OSM()';
        }

        echo '
        <script type="text/javascript" src="assets/js/jscolor.min.js"></script>
        <script type="text/javascript">
        //<![CDATA[
        $(document).ready(function() {
            if (typeof ol=="undefined")
                $("head").append($("<script />").attr({"type":"text/javascript","src":"assets/shared/ol/ol.js"}));
            $("head").append($("<script />").attr({"type":"text/javascript","src":"modules/geoloc/include/fontawesome.js"}));
            $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/assets/shared/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
            $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'/modules/geoloc/include/css/geoloc_admin.css\' type=\'text/css\' media=\'screen\'>");
        });

        jscolor.presets.default = {
            format:"rgba",
            backgroundColor:"rgba(0,0,0,1)",
            position: "bottom",
            previewSize:18,
            //width: 140,
            //height: 140,
            //paletteCols: 8,
            hideOnPaletteClick: true,
            palette: [
                "#000000", "#7d7d7d", "#870014", "#ec1c23", "#ff7e26",
                "#fef100", "#22b14b", "#00a1e7", "#3f47cc", "#a349a4",
                "#ffffff", "#c3c3c3", "#b87957", "#feaec9", "#ffc80d",
                "#eee3af", "#b5e61d", "#99d9ea", "#7092be", "#c8bfe7",
            ],
        };

        function geoloc_conf() {
        var
        w_ico_size = $("#w_ico").val(),
        h_ico_size = $("#h_ico").val();

        $(document).ready(function() {
        var para_svg = document.getElementById("para_svg");
        var para_ima = document.getElementById("para_ima");
        var img_img = document.getElementById("img_img");
        var img_svg = document.getElementById("img_svg");

        if(img_svg.checked) para_ima.classList.add("collapse");
        if(img_img.checked) para_svg.classList.add("collapse");

        img_img.addEventListener("click", function() {
            para_svg.classList.add("collapse");
            para_ima.classList.remove("collapse");
        });
        img_svg.addEventListener("click", function() {
            para_ima.classList.add("collapse");
            para_svg.classList.remove("collapse");
        });


        $( "#w_ico, #h_ico, #ch_img, #nm_img_mbg, #nm_img_mbcg, #nm_img_acg, #f_mbg" ).change(function() {
            w_ico_size = $("#w_ico").val();
            h_ico_size = $("#h_ico").val();
            i_path_mbg = $("#ch_img").val()+$("#nm_img_mbg").val();
            i_path_mbcg = $("#ch_img").val()+$("#nm_img_mbcg").val();
            i_path_acg = $("#ch_img").val()+$("#nm_img_acg").val();
            f_pa = $("#f_mbg option:selected").val();
        }).trigger("change");
        
        var 
            map_c,
            w_ico_size,
            h_ico_size,
            mark_cmbg,
            cartyp,
            mark_cmbg = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([12, 48]))}),
            mark_cmbgc = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([6, 45]))}),
            mark_cacg = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([12, 40]))}),
            mark_cmbg_svg = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([10, 10]))}),
            mark_cmbgc_svg = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([1, 47]))}),
            mark_acg_svg = new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat([5, 60]))});

        mark_cmbg.setStyle(new ol.style.Style({
            image: new ol.style.Icon({
                crossOrigin: "anonymous",
                src: "' . $ch_img . $nm_img_mbg . '",
                imgSize:[' . $w_ico_b . ',' . $h_ico_b . ']
            })
        }));
        mark_cmbgc.setStyle(new ol.style.Style({
            image: new ol.style.Icon({
                crossOrigin: "anonymous",
                src: "' . $ch_img . $nm_img_mbcg . '",
                imgSize:[' . $w_ico_b . ',' . $h_ico_b . ']
            })
        }));
        mark_cacg.setStyle(new ol.style.Style({
            image: new ol.style.Icon({
                crossOrigin: "anonymous",
                src: "' . $ch_img . $nm_img_acg . '",
                imgSize:[' . $w_ico_b . ',' . $h_ico_b . ']
            })
        }));
        mark_cmbg_svg.setStyle(new ol.style.Style({
            text: new ol.style.Text({
            text: fa("' . $f_mbg . '"),
            font: "900 ' . $mbg_sc . 'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "' . $mbg_f_co . '"}),
            stroke: new ol.style.Stroke({color: "' . $mbg_t_co . '", width: ' . $mbg_t_ep . '})
            })
        }));
        mark_cmbgc_svg.setStyle(new ol.style.Style({
            text: new ol.style.Text({
            text: fa("' . $f_mbg . '"),
            font: "900 ' . $mbgc_sc . 'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "' . $mbgc_f_co . '"}),
            stroke: new ol.style.Stroke({color: "' . $mbgc_t_co . '", width: ' . $mbgc_t_ep . '})
            })
        }));
        mark_acg_svg.setStyle(new ol.style.Style({
            text: new ol.style.Text({
            text: fa("' . $f_mbg . '"),
            font: "900 ' . $acg_sc . 'px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: "' . $acg_f_co . '"}),
            stroke: new ol.style.Stroke({color: "' . $acg_t_co . '", width: ' . $acg_t_ep . '})
            })
        }));

            var src_markers = new ol.source.Vector({
                features: [mark_cmbg, mark_cmbgc, mark_cacg, mark_cmbg_svg, mark_cmbgc_svg, mark_acg_svg]
            });
            var les_markers = new ol.layer.Vector({source: src_markers});

            var src_fond = ' . $source_fond . ';
            var fond_carte = new ol.layer.Tile({
            source: ' . $source_fond . '
            });

            var attribution = new ol.control.Attribution({collapsible: true});
            var map = new ol.Map({
                interactions: new ol.interaction.defaults({
                    constrainResolution: true, onFocusOnly: true
                }),
                controls: new ol.control.defaults({attribution: false}).extend([attribution, new ol.control.FullScreen()]),
                target: "map_conf",
                layers: [
                    fond_carte,
                    les_markers
                ],
                view: new ol.View({
                    center: ol.proj.fromLonLat([0, 45]),
                    zoom: 3
                })
            });

            var coul_temp;


        /*
        "Je suis le marker (image au format .gif .jpg .png) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9;.");
        "Je suis le marker (image au format .gif .jpg .png) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9; actuellement connecté sur le site.");
        "Je suis le marker (image au format .gif .jpg .png) symbolisant un visiteur actuellement connecté sur le site géolocalisé par son adresse IP");
        "Je suis le marker (image au format SVG) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9;");
        "Je suis le marker (image au format SVG) symbolisant un membre du site g&#xE9;or&#xE9;f&#xE9;renc&#xE9; actuellement connecté sur le site.");
        "Je suis le marker (image au format SVG) symbolisant un visiteur actuellement connecté sur le site géolocalisé par son adresse IP.");
        */

        // size dont work à revoir
        $( "#w_ico, #h_ico, #ch_img, #nm_img_mbg, #nm_img_mbcg, #nm_img_acg" ).change(function() {
            w_ico_size = $("#w_ico").val();
            h_ico_size = $("#h_ico").val();
            mark_cmbg.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    crossOrigin: "anonymous",
                    src: $("#ch_img").val()+$("#nm_img_mbg").val(),
                    imgSize:[w_ico_size,h_ico_size]
                })
            }));
            mark_cmbgc.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    crossOrigin: "anonymous",
                    src: $("#ch_img").val()+$("#nm_img_mbcg").val(),
                    imgSize:[w_ico_size,h_ico_size]
                })
            }));
            mark_cacg.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    crossOrigin: "anonymous",
                    src: $("#ch_img").val()+$("#nm_img_acg").val(),
                    imgSize:[w_ico_size,h_ico_size]
                })
            }));

            $("#v_img_mbg").html("<img width=\"22\" height=\"22\" alt=\"' . __d('geoloc', 'Image membre géoréférencé') . '\" src=\""+$("#ch_img").val()+$("#nm_img_mbg").val()+"\" />");
            $("#v_img_mbcg").html("<img width=\"22\" height=\"22\" alt=\"' . __d('geoloc', 'Image membre géoréférencé en ligne') . '\" src=\""+$("#ch_img").val()+$("#nm_img_mbcg").val()+"\" />");
            $("#v_img_acg").html("<img width=\"22\" height=\"22\" alt=\"' . __d('geoloc', 'Image anonyme géoréférencé en ligne') . '\" src=\""+$("#ch_img").val()+$("#nm_img_acg").val()+"\" />");
        })


        var changestyle = function(m,f_fa,fc,tc,sc) {
        m.setStyle(new ol.style.Style({
            text: new ol.style.Text({
            text: fa(f_fa),
            font: "900 "+sc+"px \'Font Awesome 5 Free\'",
            bottom: "Bottom",
            fill: new ol.style.Fill({color: fc}),
            stroke: new ol.style.Stroke({color: tc, width: ' . $mbg_t_ep . '})
            })
        }));
        }

        //==> change font on the map
        $("#f_mbg").change(function(event) {
            var
                f_fa = $("#f_mbg option:selected").val(),
                fc_m = $("#mbg_f_co").val(),
                fc_mo = $("#mbgc_f_co").val(),
                fc_a = $("#acg_f_co").val(),
                tc_m = $("#mbg_t_co").val(),
                tc_mo = $("#mbgc_t_co").val(),
                tc_a = $("#acg_t_co").val(),
                sc_m = $("#mbg_sc option:selected").val(),
                sc_mo = $("#mbgc_sc option:selected").val(),
                sc_a = $("#acg_sc option:selected").val();

            changestyle(mark_cmbg_svg,f_fa,fc=fc_m,tc=tc_m,sc=sc_m);
            changestyle(mark_cmbgc_svg,f_fa,fc=fc_mo,tc=tc_mo,sc=sc_mo);
            changestyle(mark_acg_svg,f_fa,fc=fc_a,tc=tc_a,sc=sc_a);
            $("#f_choice_mbg,#f_choice_mbgc,#f_choice_acg").html(fa(f_fa));
            $("#vis_ic").html(\'<span id="fontchoice" class="fa fa-lg">\'+fa(f_fa)+\'</span>\');
        })

        $("#ch_img, #img_mbgb").change(function() {
            $("#v_img_mbgb").html("<img width=\"22\" height=\"22\" alt=\"' . __d('geoloc', 'Image membre géoréférencé') . '\" src=\""+$("#ch_img").val()+$("#img_mbgb").val()+"\" />");
        })
        //==> aux changements de taille
            $("#mbg_sc").change(function() {
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#mbg_f_co").val();
            var tc = $("#mbg_t_co").val();
            var sc = $("#mbg_sc option:selected").val();
            changestyle(mark_cmbg_svg,f_fa,fc,tc,sc);
            });
        $("#mbgc_sc").change(function() {
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#mbgc_f_co").val();
            var tc = $("#mbgc_t_co").val();
            var sc = $("#mbgc_sc option:selected").val();
            changestyle(mark_cmbgc_svg,f_fa,fc,tc,sc);
        });
        $("#acg_sc").change(function() {
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#acg_f_co").val();
            var tc = $("#acg_t_co").val();
            var sc = $("#acg_sc option:selected").val();
            changestyle(mark_acg_svg,f_fa,fc,tc,sc);
        });
        //<== aux changements de taille

        //==> aux changements de couleurs fond
        $("#mbg_f_co").change(function(){
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#mbg_f_co").val();
            var tc = $("#mbg_t_co").val();
            var sc = $("#mbg_sc option:selected").val();
            changestyle(mark_cmbg_svg,f_fa,fc,tc,sc);
            $("#f_choice_mbg").attr("style","color:"+fc);
        });
        $("#mbgc_f_co").change(function(){
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#mbgc_f_co").val();
            var tc = $("#mbgc_t_co").val();
            var sc = $("#mbgc_sc option:selected").val();
            changestyle(mark_cmbgc_svg,f_fa,fc,tc,sc);
            $("#f_choice_mbgc").attr("style","color:"+fc);
        });
        $("#acg_f_co").change(function(){
            var f_fa = $("#f_mbg option:selected").val();
            var fc = $("#acg_f_co").val();
            var tc = $("#acg_t_co").val();
            var sc = $("#acg_sc option:selected").val();
            changestyle(mark_acg_svg,f_fa,fc,tc,sc);
            $("#f_choice_acg").attr("style","color:"+fc);
        });
        //<== aux changements de couleurs fond
        /*
            $("#mbg_f_op").change(function() {
                icon_mbg_svg.fillOpacity = Number($("#mbg_f_op").val());
                mark_cmbg_svg.setIcon(icon_mbg_svg);
            });
            $("#mbgc_f_op").change(function() {
                icon_cmbg_svg.fillOpacity = Number($("#mbgc_f_op").val());
                mark_cmbgc_svg.setIcon(icon_cmbg_svg);
            });
            $("#acg_f_op").change(function() {
                icon_cacg_svg.fillOpacity = Number($("#acg_f_op").val());
                mark_acg_svg.setIcon(icon_cacg_svg);
            });

            $("#mbg_t_op").change(function() {
                icon_mbg_svg.strokeOpacity = Number($("#mbg_t_op").val());
                mark_cmbg_svg.setIcon(icon_mbg_svg);
            });
            $("#mbgc_t_op").change(function() {
                icon_cmbg_svg.strokeOpacity = Number($("#mbgc_t_op").val());
                mark_cmbgc_svg.setIcon(icon_cmbg_svg);
            });
            $("#acg_t_op").change(function() {
                icon_cacg_svg.strokeOpacity = Number($("#acg_t_op").val());
                mark_acg_svg.setIcon(icon_cacg_svg);
            });

            $("#mbg_t_ep").change(function() {
                icon_mbg_svg.strokeWeight = Number($("#mbg_t_ep").val());
                mark_cmbg_svg.setIcon(icon_mbg_svg);
            });
            $("#mbgc_t_ep").change(function() {
                icon_cmbg_svg.strokeWeight = Number($("#mbgc_t_ep").val());
                mark_cmbgc_svg.setIcon(icon_cmbg_svg);
            });
            $("#acg_t_ep").change(function() {
                icon_cacg_svg.strokeWeight = Number($("#acg_t_ep").val());
                mark_acg_svg.setIcon(icon_cacg_svg);
            });
        */

        /*
            $(".pickcol_tmb").colorpicker().on("changeColor.colorpicker", function(event){
                var coul = event.color.toHex()
                icon_mbg_svg.strokeColor=coul;
                mark_cmbg_svg.setIcon(icon_mbg_svg);
            });
            $(".pickcol_tmbc").colorpicker().on("changeColor.colorpicker", function(event){
                var coul = event.color.toHex()
                icon_cmbg_svg.strokeColor=coul;
                mark_cmbgc_svg.setIcon(icon_cmbg_svg);
            });
            $(".pickcol_tac").colorpicker().on("changeColor.colorpicker", function(event){
                var coul = event.color.toHex()
                icon_cacg_svg.strokeColor=coul;
                mark_acg_svg.setIcon(icon_cacg_svg);
            });
        */

        $("#cartyp").on("change", function() {
            cartyp = $( "#cartyp option:selected" ).val();
            switch (cartyp) {
                case "OSM":
                    fond_carte.setSource(new ol.source.OSM());
                break;
                case "Road":case "Aerial":case "AerialWithLabels":
                    fond_carte.setSource(new ol.source.BingMaps({key: "' . $api_key_bing . '",imagerySet: cartyp }));
                break;
                case "natural-earth-hypso-bathy": case "geography-class":
                    fond_carte.setSource(new ol.source.TileJSON({url: "https://api.tiles.mapbox.com/v4/mapbox."+cartyp+".json?access_token=' . $api_key_mapbox . '"}));
                break;
                case "terrain": case "toner": case "watercolor":
                    fond_carte.setSource(new ol.source.Stamen({layer:cartyp}));
                break;
                case "sat-google":
                    fond_carte.setSource(new ol.source.XYZ({url: "https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",crossOrigin: "Anonymous", attributions: " &middot; <a href=\"https://www.google.at/permissions/geoguidelines/attr-guide.html\">Map data ©2015 Google</a>"}));
                break;
                case "World_Imagery":case "World_Shaded_Relief":case "World_Physical_Map":case "World_Topo_Map":
                    fond_carte.setSource(new ol.source.XYZ({
                    attributions: ["Powered by Esri", "Source: Esri, DigitalGlobe, GeoEye, Earthstar Geographics, CNES/Airbus DS, USDA, USGS, AeroGRID, IGN, and the GIS User Community"],
                    attributionsCollapsible: true,
                    url: "https://services.arcgisonline.com/ArcGIS/rest/services/"+cartyp+"/MapServer/tile/{z}/{y}/{x}",
                    maxZoom: 23
                }));
                break;
            }
        });

        });
        }

        window.onload = geoloc_conf;

        //]]>
        </script>';

        adminfoot('', '', '', '');
    }

}
