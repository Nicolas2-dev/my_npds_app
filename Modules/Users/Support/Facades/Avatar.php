<?php

namespace Modules\Users\Support\Facades;

use Modules\Users\Library\AvatarManager;

/**
 * Undocumented class
 */
class Avatar
{

    public static function __callStatic($method, $parameters)
    {
        $instance = AvatarManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
