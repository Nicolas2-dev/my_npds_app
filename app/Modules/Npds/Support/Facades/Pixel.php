<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\PixelManager;


class Pixel
{

    public static function __callStatic($method, $parameters)
    {
        $instance = PixelManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}