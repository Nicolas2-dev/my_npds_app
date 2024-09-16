<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\SessionManager;


class Session
{

    public static function __callStatic($method, $parameters)
    {
        $instance = SessionManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}