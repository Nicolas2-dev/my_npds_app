<?php

namespace App\Modules\download\Support\Facades;

use App\Modules\download\Library\DownloadManager;


class Download
{

    public static function __callStatic($method, $parameters)
    {
        $instance = DownloadManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}