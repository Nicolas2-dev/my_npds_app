<?php


use Npds\Events\Manager as Events;

/**
 * Counptage des pages vues;
 */
Events::addListener('counter', 'Modules\Npds\Controllers\Api\ApiCounter@update');

/**
 * Enregistrement des referers
 */
Events::addListener('referer', 'Modules\Npds\Controllers\Api\ApiReferer@update');

