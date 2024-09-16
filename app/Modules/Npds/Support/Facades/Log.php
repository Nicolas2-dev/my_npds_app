<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\LogManager;


class Log
{

    public static function __callStatic($method, $parameters)
    {
        $instance = LogManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}