<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\UrlManager;


class Url
{

    public static function __callStatic($method, $parameters)
    {
        $instance = UrlManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}