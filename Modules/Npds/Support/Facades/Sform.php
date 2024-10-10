<?php

namespace Modules\Npds\Support\Facades;

use Modules\Npds\Library\Sform\SformManager;

/**
 * Undocumented class
 */
class Sform
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
        $instance = new SformManager();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
