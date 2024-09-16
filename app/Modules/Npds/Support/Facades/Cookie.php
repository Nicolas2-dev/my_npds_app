<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\CookieManager;


class Cookie
{

    public static function __callStatic($method, $parameters)
    {
        $instance = CookieManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}