<?php

namespace Modules\download\Support\Facades;

use Modules\download\Library\DownloadManager;

/**
 * Undocumented class
 */
class Download
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
        $instance = DownloadManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
