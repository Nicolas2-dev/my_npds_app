<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\HackManager;

/**
 * Undocumented class
 */
class Hack
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
        $instance = HackManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
