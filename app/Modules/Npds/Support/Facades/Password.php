<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\PasswordManager;

/**
 * Undocumented class
 */
class Password
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
        $instance = PasswordManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
