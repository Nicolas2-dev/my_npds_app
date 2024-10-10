<?php

namespace Modules\Geoloc\Support\Facades;

use Modules\Geoloc\Library\GeolocManager;

/**
 * Undocumented class
 */
class Geoloc
{
    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = GeolocManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
