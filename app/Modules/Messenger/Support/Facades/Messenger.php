<?php

namespace App\Modules\Messenger\Support\Facades;

use App\Modules\Messenger\Library\MessengerManager;


class Messenger
{

    public static function __callStatic($method, $parameters)
    {
        $instance = MessengerManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}