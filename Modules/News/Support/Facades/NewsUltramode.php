<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsUltramodeManager;

/**
 * Undocumented class
 */
class NewsUltramode
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
        $instance = NewsUltramodeManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
