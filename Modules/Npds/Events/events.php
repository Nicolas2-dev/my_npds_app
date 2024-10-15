<?php


use Npds\Events\Manager as Events;

/**
 * Init database Mysqli Deprecated
 */
Events::addListener('db_mysqli_deprecated', 'Modules\Npds\Controllers\Api\ApiDatabase@mysqli_deprecated');

/**
 * Counptage des pages vues;
 */
Events::addListener('counter', 'Modules\Npds\Controllers\Api\ApiCounter@update');

/**
 * Enregistrement des referers
 */
Events::addListener('referer', 'Modules\Npds\Controllers\Api\ApiReferer@update');

/**
 * Spam boot
 */
Events::addListener('spamboot', 'Modules\Npds\Controllers\Api\ApiSpam@bootmanage');

/**
 * Enregistrement des referers
 */
Events::addListener('language', 'Modules\Npds\Controllers\Api\ApiLanguage@manage');

/**
 * Enregistrement des referers
 */
Events::addListener('session', 'Modules\Npds\Controllers\Api\ApiSession@manage');