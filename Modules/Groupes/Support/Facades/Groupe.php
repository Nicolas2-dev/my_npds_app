<?php

namespace Modules\Groupes\Support\Facades;

use Modules\Groupes\Library\GroupeManager;


class Groupe
{

    public static function __callStatic($method, $parameters)
    {
        $instance = GroupeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}