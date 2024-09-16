<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\JsManager;


class Js
{

    public static function __callStatic($method, $parameters)
    {
        $instance = JsManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}