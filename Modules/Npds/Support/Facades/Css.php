<?php

namespace Modules\Npds\Support\Facades;

use Modules\Npds\Library\CssManager;

/**
 * Undocumented class
 */
class Css
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
        $instance = CssManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
