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
