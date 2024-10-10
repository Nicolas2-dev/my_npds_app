<?php

namespace Modules\Users\Support\Facades;

use Modules\Users\Library\OnlineManager;


class Online
{

    public static function __callStatic($method, $parameters)
    {
        $instance = OnlineManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}