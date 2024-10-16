<?php

namespace Modules\Push\Support\Facades;

use Modules\Push\Library\PushManager;

/**
 * Undocumented class
 */
class Push
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
        $instance = PushManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
