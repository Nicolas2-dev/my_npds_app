<?php

namespace Modules\Comments\Support\Facades;

use Modules\Comments\Library\CommentManager;

/**
 * Undocumented class
 */
class Comment
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
        $instance = CommentManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
