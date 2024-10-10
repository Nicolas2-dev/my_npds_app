<?php
/**
 * Npds - Modules/Geoloc/contracts/GeolocInterface.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */
namespace Modules\Geoloc\Contracts;

/**
 * Undocumented interface
 */
interface GeolocInterface {

    /**
     * Undocumented function
     *
     * @param [type] $uname
     * @param [type] $posterdata_extend
     * @return void
     */
    public function geoloc_carte($uname, $posterdata_extend);

}
