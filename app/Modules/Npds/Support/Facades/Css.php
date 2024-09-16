<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\CssManager;


class Css
{

    public static function __callStatic($method, $parameters)
    {
        $instance = CssManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}