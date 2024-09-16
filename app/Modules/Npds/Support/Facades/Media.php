<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\MediaManager;


class Media
{

    public static function __callStatic($method, $parameters)
    {
        $instance = MediaManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}