<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\DateManager;


class Date
{

    public static function __callStatic($method, $parameters)
    {
        $instance = DateManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}