<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\CryptManager;


class Crypt
{

    public static function __callStatic($method, $parameters)
    {
        $instance = CryptManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}