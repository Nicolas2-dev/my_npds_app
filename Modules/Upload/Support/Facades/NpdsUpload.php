<?php

namespace Modules\Upload\Support\Facades;

use Modules\Upload\Library\NpdsUploadManager;

/**
 * Undocumented class
 */
class NpdsUpload
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
        $instance = NpdsUploadManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }

}
