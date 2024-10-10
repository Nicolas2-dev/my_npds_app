<?php

namespace Modules\Stats\Support\Facades;

use Modules\Stats\Library\StatManager;

class Stat
{

    public static function __callStatic($method, $parameters)
    {
        $instance = StatManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
