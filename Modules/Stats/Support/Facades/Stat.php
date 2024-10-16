<?php

namespace Modules\Stats\Support\Facades;

use Modules\Stats\Library\StatManager;

/**
 * Undocumented class
 */
class Stat
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
        $instance = StatManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
