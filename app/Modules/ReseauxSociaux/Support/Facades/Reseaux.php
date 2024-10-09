<?php

namespace App\Modules\ReseauxSociaux\Support\Facades;

use App\Modules\ReseauxSociaux\Library\ReseauxManager;

/**
 * Undocumented class
 */
class Reseaux
{

    public static function __callStatic($method, $parameters)
    {
        $instance = ReseauxManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
