<?php

namespace Modules\Wspad\Support\Facades;

use Modules\Wspad\Library\WspadManager;

/**
 * Undocumented class
 */
class Wspad
{

    public static function __callStatic($method, $parameters)
    {
        $instance = WspadManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
