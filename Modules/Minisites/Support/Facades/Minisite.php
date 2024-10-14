<?php

namespace Modules\Minisites\Support\Facades;

use Modules\Minisites\Library\MinisiteManager;

/**
 * Undocumented class
 */
class Minisite
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
        $instance = MinisiteManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
