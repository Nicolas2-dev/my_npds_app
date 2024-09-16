<?php

namespace App\Modules\Npds\Support\Facades;

use App\Modules\Npds\Library\MailerManager;


class Mailer
{

    public static function __callStatic($method, $parameters)
    {
        $instance = MailerManager::getInstance();

        return call_user_func_array(array($instance, $method), $parameters);
    }
}