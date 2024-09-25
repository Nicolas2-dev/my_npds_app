<?php

use Npds\Routing\Router;


Router::post('api/alerte', 'App\Modules\Npds\Controllers\Api\ApiAlerte@alerte_api');
