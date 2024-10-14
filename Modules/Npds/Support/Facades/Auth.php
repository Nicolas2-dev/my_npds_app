<?php

namespace Modules\Npds\Support\Facades;

use Modules\Npds\Library\AuthManager;

/**
 * Undocumented class
 */
class Auth
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
        $instance = AuthManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
