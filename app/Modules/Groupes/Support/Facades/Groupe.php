<?php

namespace App\Modules\Groupes\Support\Facades;

use App\Modules\Groupes\Library\GroupeManager;


class Groupe
{

    public static function __callStatic($method, $parameters)
    {
        $instance = GroupeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}