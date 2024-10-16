<?php

namespace Modules\Pollbooth\Support\Facades;

use Modules\Pollbooth\Library\PollboothManager;

/**
 * Undocumented class
 */
class Pollbooth
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
        $instance = PollboothManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
