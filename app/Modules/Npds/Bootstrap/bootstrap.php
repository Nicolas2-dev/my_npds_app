<?php


$configDir = dirname(dirname(__FILE__)) .DS;

//
include $configDir .'constants.php';

include $configDir .'Support/helpers.php';

include $configDir .'Support/Metalang.php';

include $configDir .'Boxe/Boxe.php';

include $configDir .'Routes/web/routes.php';

include $configDir .'Routes/api/routes.php';

include $configDir .'Routes/admin/routes.php';
