<?php

namespace Modules\ReseauxSociaux\Support\Facades;

use Modules\ReseauxSociaux\Library\ReseauxManager;

/**
 * Undocumented class
 */
class Reseaux
{
    /**
     * Undocumented function
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = ReseauxManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
