<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsAutomatedManager;

/**
 * Undocumented class
 */
class NewsAutomated
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
        $instance = NewsAutomatedManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
