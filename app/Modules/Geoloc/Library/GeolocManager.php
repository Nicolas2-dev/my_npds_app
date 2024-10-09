<?php

namespace App\Modules\Geoloc\Library;

use Npds\view\View;
use Npds\Config\Config;
use App\Modules\Geoloc\Contracts\GeolocInterface;

/**
 * Undocumented class
 */
class GeolocManager implements GeolocInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }
     
    /**
     * file_contents_exist() : Controle de réponse
     * 
     * c'est pas encore assez fin not work with https probably
     *
     * @param [type] $url
     * @param integer $response_code
     * @return void
     */
    public function file_contents_exist($url, $response_code = 200)
    {
        $headers = get_headers($url);

        if (substr($headers[0], 9, 3) == $response_code) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * construit la carte pour l'ip géoréférencée ($iptoshow) à localiser
     *
     * @param [type] $iptoshow
     * @return void
     */
    public function localiser_ip($iptoshow)
    {
        include('modules/geoloc/geoloc.conf');

        global $iptoshow;

        $aff_location = '';

        if ($geo_ip == 1) {
            $ip_location = sql_query("SELECT * FROM ip_loc WHERE ip_ip LIKE \"" . $iptoshow . "\"");

            if (sql_num_rows($ip_location) !== 0) {
                $row = sql_fetch_assoc($ip_location);

                $aff_location .= '
                <div class="col-md-5">
                    <div id="map_ip" style=" min-height:240px;" lang="' . language_iso(1, 0, 0) . '"></div>
                </div>
                <script type="module">
                //<![CDATA[
                    if (!$("link[href=\'/assets/shared/ol/ol.css\']").length)
                        $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'assets/shared/ol/ol.css\' type=\'text/css\' media=\'screen\'>");
                    $("head link[rel=\'stylesheet\']").last().after("<link rel=\'stylesheet\' href=\'modules/geoloc/include/css/geoloc_locip.css\' type=\'text/css\' media=\'screen\'>");
                    if (typeof ol=="undefined")
                        $("head").append($("<script />").attr({"type":"text/javascript","src":"assets/shared/ol/ol.js"}));

                $(function(){
                    function pointStyleFunction(feature, resolution) {
                    return  new ol.style.Style({
                        image: new ol.style.Circle({
                        radius: 30,
                        fill: new ol.style.Fill({color: "rgba(255, 0, 0, 0.1)"}),
                        stroke: new ol.style.Stroke({color: "red", width: 1})
                        })
                    });
                    }
                    var
                        ipPoint = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([' . $row['ip_long'] . ',' . $row['ip_lat'] . '])),
                        name: "IP"
                        }),
                        iconStyle = new ol.style.Style({
                        image: new ol.style.Icon(({
                            src: "' . $ch_img . 'ip_loc.svg",
                            size:[100,100]
                        }))
                        }),
                        vectorSource = new ol.source.Vector({
                        features: [ipPoint]
                        }),
                        vectorLayer = new ol.layer.Vector({
                        source: vectorSource,
                        style: pointStyleFunction
                        }),
                        Controls = new ol.control.defaults,
                        fullscreen = new ol.control.FullScreen();
                    var map = new ol.Map({
                        controls: Controls.extend([
                        fullscreen
                        ]),
                    target: document.getElementById("map_ip"),
                    layers: [
                        new ol.layer.Tile({
                        source: new ol.source.OSM()
                        }),
                        vectorLayer
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([' . $row['ip_long'] . ',' . $row['ip_lat'] . ']),
                        zoom: 12
                    })
                    });';

                $aff_location .= file_get_contents('modules/geoloc/include/ol-dico.js');

                $aff_location .= '
                const targ = map.getTarget();
                const lang = targ.lang;
                for (var i in dic) {
                    if (dic.hasOwnProperty(i)) {
                    $("#map_ip "+dic[i].cla).prop("title", dic[i][lang]);
                    }
                }
                fullscreen.on("enterfullscreen",function(){
                    $(dic.olfullscreentrue.cla).attr("data-original-title", dic["olfullscreentrue"][lang]);
                })
                fullscreen.on("leavefullscreen",function(){
                    $(dic.olfullscreenfalse.cla).attr("data-original-title", dic["olfullscreenfalse"][lang]);
                })

                $("#map_ip .ol-zoom-in, #map_ip .ol-zoom-out").tooltip({placement: "right", container: "#map_ip",});
                $("#map_ip .ol-full-screen-false, #map_ip .ol-rotate-reset, #map_ip .ol-attribution button[title]").tooltip({placement: "left", container: "#map_ip",});
                });
            //]]>
            </script>';
            }
        }

        return $aff_location;
    }

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @param [type] $posterdata_extend
     * @return void
     */
    public function geoloc_carte($uname, $posterdata_extend)
    {
        $ch_lat = Config::get('geoloc.config.ch_lat'); 
        $ch_lon = Config::get('geoloc.config.ch_lon'); 

        if (array_key_exists($ch_lat, $posterdata_extend) and array_key_exists($ch_lon, $posterdata_extend)) {
            if ($posterdata_extend[$ch_lat] != '' and $posterdata_extend[$ch_lon] != '') {
                
                //
                return View::make('Modules/Geoloc/Views/Partials/geoloc_carte', [
                    'longitude'             => $posterdata_extend[$ch_lon],
                    'latitude'              => $posterdata_extend[$ch_lat],
                    'uname'                 => $uname,
                    'import_geoloc_data_js' => file_get_contents(module_path('Geoloc/assets/js/ol-dico.js')),

                ]);
            }
        }
    }

}
