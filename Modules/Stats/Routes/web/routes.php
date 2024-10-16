<?php

use Npds\Routing\Router;

// Stats
Router::get('stats',    'Modules\Stats\Controllers\Front\Stats\Stats@index');

// Top
Router::get('top',    'Modules\Stats\Controllers\Front\Tops\Top@index');