<?php

/**
 * Npds - Modules/Users/Bootstrap/bootstrap.php
 *
 * @author  Nicolas Devoy
 * @email   nicolas@nicodev.fr 
 * @version 1.0.0
 * @date    26 Septembre 2024
 */
use Modules\Users\Bootstrap\UsersKernel;
 
UsersKernel::aliases_loader();

$configDir = dirname(dirname(__FILE__)) .DS;

//
include $configDir .'constants.php';

include $configDir .'Support/helpers.php';

include $configDir .'Boxe/Boxe.php';

include $configDir .'Events/events.php';

include $configDir .'Routes/filters.php';

include $configDir .'Routes/web/routes.php';

include $configDir .'Routes/admin/routes.php';
