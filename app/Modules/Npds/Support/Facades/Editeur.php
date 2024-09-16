<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\EditeurManager;



class Editeur
{

    public static function __callStatic($method, $parameters)
    {
        $instance = EditeurManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}