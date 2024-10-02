<?php

use App\Modules\Geoloc\Bootstrap\GeolocKernel;

GeolocKernel::aliases_loader();

$configDir = dirname(dirname(__FILE__)) .DS;

//
include $configDir .'constants.php';

//include $configDir .'config/config.php';

include $configDir .'Routes/web/routes.php';

include $configDir .'Routes/admin/routes.php';
