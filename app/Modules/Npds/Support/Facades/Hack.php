<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\HackManager;


class Hack
{

    public static function __callStatic($method, $parameters)
    {
        $instance = HackManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}