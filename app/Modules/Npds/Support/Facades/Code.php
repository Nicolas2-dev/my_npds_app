<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\CodeManager;


class Code
{

    public static function __callStatic($method, $parameters)
    {
        $instance = CodeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}