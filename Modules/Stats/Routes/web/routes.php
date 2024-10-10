<?php

use Npds\Routing\Router;

// Stats
Router::get('stats',    'Modules\Stats\Controllers\Front\FrontStats@index');

// Top
Router::get('top',    'Modules\Stats\Controllers\Front\FrontTop@index');