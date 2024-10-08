<?php

use Npds\Routing\Router;

// Stats
Router::get('stats',    'App\Modules\Stats\Controllers\Front\FrontStats@index');

// Top
Router::get('top',    'App\Modules\Stats\Controllers\Front\FrontTop@index');