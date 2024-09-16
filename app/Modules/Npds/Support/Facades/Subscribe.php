<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\SubscribeManager;


class Subscribe
{

    public static function __callStatic($method, $parameters)
    {
        $instance = SubscribeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}