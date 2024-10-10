<?php

namespace Modules\Pollbooth\Support\Facades;

use Modules\Pollbooth\Library\PollboothManager;


class Pollbooth
{

    public static function __callStatic($method, $parameters)
    {
        $instance = PollboothManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}