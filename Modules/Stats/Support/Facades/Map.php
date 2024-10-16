<?php

namespace Modules\Stats\Support\Facades;

use Modules\Stats\Library\MapManager;

/**
 * Undocumented class
 */
class Map
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
        $instance = MapManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
