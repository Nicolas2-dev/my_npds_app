<?php

namespace App\Modules\Blocks\Support\Facades;

use App\Modules\Blocks\Library\BlockManager;

/**
 * Undocumented class
 */
class Block
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
        $instance = BlockManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
