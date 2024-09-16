<?php


use Npds\config\Config;
use Npds\Events\Manager as Events;


Events::addListener('UserInfo', 'App\Modules\Users\Controllers\Front\UserInfo@index');