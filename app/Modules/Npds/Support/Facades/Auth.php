<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\AuthManager;


class Auth
{

    public static function __callStatic($method, $parameters)
    {
        $instance = AuthManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}