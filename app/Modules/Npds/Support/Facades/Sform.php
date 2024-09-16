<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\Sform\SformManager;


class Sform
{

    public static function __callStatic($method, $parameters)
    {
        $instance = new SformManager();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}