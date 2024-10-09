<?php

use Npds\Routing\Router;

/**
 * 
 */
Router::post('api/alerte', 'App\Modules\Npds\Controllers\Api\ApiAlerte@alerte_api');

/**
 * 
 */
Router::get('error', 'App\Modules\Npds\Controllers\Error\AccessError@access_error');

/**
 * 
 */
Router::get('denied', 'App\Modules\Npds\Controllers\Error\AccessError@access_denied');
