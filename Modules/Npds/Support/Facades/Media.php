<?php

namespace Modules\Npds\Support\Facades;

use Modules\Npds\Library\MediaManager;

/**
 * Undocumented class
 */
class Media
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
        $instance = MediaManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
