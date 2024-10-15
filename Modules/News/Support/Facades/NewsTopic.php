<?php

namespace Modules\News\Support\Facades;

use Modules\News\Library\NewsTopicManager;

/**
 * Undocumented class
 */
class NewsTopic
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
        $instance = NewsTopicManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
