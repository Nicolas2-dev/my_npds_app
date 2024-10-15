<?php

use Npds\Config\Config;
use Modules\Npds\Bootstrap\NpdsKernel;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Npds\Support\Facades\Session;
use Npds\Events\Manager as Events;
use Modules\Npds\Support\Facades\Language;

NpdsKernel::aliases_loader();

$configDir = dirname(dirname(__FILE__)) .DS;

//
include $configDir .'constants.php';

include $configDir .'Support/helpers.php';

include $configDir .'Support/Metalang.php';

include $configDir .'Boxe/Boxe.php';

include $configDir .'Events/events.php';

include $configDir .'Routes/web/routes.php';

include $configDir .'Routes/api/routes.php';

include $configDir .'Routes/admin/routes.php';

