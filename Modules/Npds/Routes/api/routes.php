<?php

use Npds\Routing\Router;

/**
 * 
 */
Router::post('api/alerte', 'Modules\Npds\Controllers\Api\ApiAlerte@alerte_api');

/**
 * 
 */
Router::get('error', 'Modules\Npds\Controllers\Error\AccessError@access_error');

/**
 * 
 */
Router::get('denied', 'Modules\Npds\Controllers\Error\AccessError@access_denied');
