<?php

namespace Modules\Geoloc\Controllers;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class GeolocRefip extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
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
     * @return void
     */
    public function ref_ip()
    {
        #autodoc geoloc_refip : contrôle si l'ip est déjà dans la base et incrémentation du compteur de visite de l'ip 
        # ou choisi un fournisseur en fonction du protocol du site et des clefs disponibles et complète la table ip_loc. 
        $file_path = array(
            'https://ipapi.co/'.urldecode($ip).'/json/',
            'https://api.ipdata.co/'.urldecode($ip).'?api-key='.$api_key_ipdata,
            'https://extreme-ip-lookup.com/json/'.urldecode($ip).'?key='.$key_lookup,
            'http://ip-api.com/json/'.urldecode($ip),
            'http://extreme-ip-lookup.com/json/'.urldecode($ip).'?key='.$key_lookup
        );

        $ousursit='';
        $resultat=sql_query("SELECT * FROM ip_loc WHERE ip_ip LIKE \"$ip\"");
        $controle=sql_num_rows($resultat);

        while ($row = sql_fetch_array($resultat)) {
            $ousursit= preg_replace("#/.*?/#",'',$_SERVER['PHP_SELF']);
        }

        if($controle != 0)
            sql_query("UPDATE ip_loc SET ip_visite= ip_visite +1 , ip_visi_pag = \"$ousursit\" WHERE ip_ip LIKE \"$ip\" ");
        else {
            $ibid = false;

            if (strstr(Config::get('npds.nuke_url'), 'https')) {
                if (file_contents_exist($file_path[0])) {
                    $loc = file_get_contents($file_path[0]);
                    $loc_obj = json_decode($loc);

                    if ($loc_obj) {
                        $error = property_exists($loc_obj, "error");

                        if ($error === false) {
                            $ibid=true;
                            $pay= !empty($loc_obj->country_name)? removeHack($loc_obj->country_name): '';
                            $codepay= !empty($loc_obj->country)? removeHack($loc_obj->country): '';
                            $vi= !empty($loc_obj->city)? removeHack($loc_obj->city): '';
                            $lat= !empty($loc_obj->latitude)? (float)$loc_obj->latitude: ''; 
                            $long= !empty($loc_obj->longitude)? (float)$loc_obj->longitude: '';
                        }
                    }
                }

                if($ibid===false and $api_key_ipdata !='') {
                    if(file_contents_exist($file_path[1])) {

                        $loc = file_get_contents($file_path[1]);
                        $loc_obj = json_decode($loc);

                        if($loc_obj) {
                            $error = property_exists($loc_obj, "message");

                            if($error === false) {
                                $ibid=true;
                                $pay= !empty($loc_obj->country_name)? removeHack($loc_obj->country_name): '';
                                $codepay= !empty($loc_obj->country_code)? removeHack($loc_obj->country_code): '';
                                $vi= !empty($loc_obj->city)? removeHack($loc_obj->city): '';
                                $lat= !empty($loc_obj->latitude)? (float)$loc_obj->latitude: '';
                                $long= !empty($loc_obj->longitude)? (float)$loc_obj->longitude: '';
                            }
                        }
                    }
                }

                if($ibid===false and $key_lookup !='') {
                    if(file_contents_exist($file_path[2])) {

                        $loc = file_get_contents($file_path[2]);
                        $loc_obj = json_decode($loc);

                        if ($loc_obj->status=='success') {
                            $ibid=true;
                            $pay= !empty($loc_obj->country)? removeHack($loc_obj->country): '';
                            $codepay= !empty($loc_obj->countryCode)? removeHack($loc_obj->countryCode): '';
                            $vi= !empty($loc_obj->city)? removeHack($loc_obj->city): '';
                            $lat= !empty($loc_obj->lat)? (float)$loc_obj->lat: '';
                            $long= !empty($loc_obj->lon)? (float)$loc_obj->lon: '';
                        }
                    }
                }
            }
            elseif(strstr(Config::get('npds.nuke_url'),'http')) {

                if(file_contents_exist($file_path[3])) {
                    $loc = file_get_contents($file_path[3]);
                    $loc_obj = json_decode($loc);

                    if($loc_obj) {
                        if ($loc_obj->status=='success') {
                            $ibid=true;
                            $pay= !empty($loc_obj->country)? removeHack($loc_obj->country): '';
                            $codepay= !empty($loc_obj->countryCode)? removeHack($loc_obj->countryCode): '';
                            $vi= !empty($loc_obj->city)? removeHack($loc_obj->city): '';
                            $lat= !empty($loc_obj->lat)? (float)$loc_obj->lat: '';
                            $long= !empty($loc_obj->lon)? (float)$loc_obj->lon: '';
                        }
                    }
                }

                if($ibid===false and $key_lookup !='') {
                    if(file_contents_exist($file_path[4])) {

                        $loc = file_get_contents($file_path[4]);
                        $loc_obj = json_decode($loc);

                        if ($loc_obj->status=='success') {
                            $ibid=true;
                            $pay= !empty($loc_obj->country)? removeHack($loc_obj->country): '';
                            $codepay= !empty($loc_obj->countryCode)? removeHack($loc_obj->countryCode): '';
                            $vi= !empty($loc_obj->city)? removeHack($loc_obj->city): '';
                            $lat= !empty($loc_obj->lat)? (float)$loc_obj->lat: '';
                            $long= !empty($loc_obj->lon)? (float)$loc_obj->lon: '';
                        }
                    }
                }
            }

            if ($ibid===false)
            return ;
            else {
            sql_query("INSERT INTO ip_loc (ip_long, ip_lat, ip_ip, ip_country, ip_code_country, ip_city) VALUES ('$long', '$lat', '$ip', '$pay', '$codepay', '$vi')");
            sql_query("UPDATE ip_loc SET ip_visite= ip_visite +1, ip_visi_pag = \"$ousursit\" WHERE ip_ip LIKE \"$ip\" ");
            }
        }
    }

}
