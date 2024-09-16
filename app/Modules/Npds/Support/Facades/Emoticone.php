<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\EmoticoneManager;


class Emoticone
{

    public static function __callStatic($method, $parameters)
    {
        $instance = EmoticoneManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}