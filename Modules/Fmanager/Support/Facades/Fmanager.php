<?php

namespace Modules\Fmanager\Support\Facades;

use Modules\Fmanager\Library\FManager as FmanagerManager;

/**
 * Undocumented class
 */
class Fmanager
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
        $instance = FmanagerManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
