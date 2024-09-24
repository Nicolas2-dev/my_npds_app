<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\PixelManager;

/**
 * Undocumented class
 */
class Pixel
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
        $instance = PixelManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
