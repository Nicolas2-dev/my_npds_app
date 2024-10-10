<?php

use Npds\Log\Logger;
use Npds\Config\config;
use Npds\Routing\Router;
use Npds\Console\Console;
use Npds\Session\Session;
use Npds\Foundation\AliasLoader;
use Npds\Events\Manager as Events;
use Npds\Themes\Manager as Themes;
use Modules\Npds\Support\Debug;
use Npds\Modules\Manager as Modules;
use Npds\Packages\Manager as Packages;

// Store the Framework's starting time in a define.
define('FRAMEWORK_STARTING_MICROTIME', microtime(true));

//
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

//
define('BASEPATH', dirname(__DIR__) .DS);

//
define('WEBPATH', dirname(__FILE__) .DS);

//
define('APPPATH', BASEPATH .'app' .DS);

//
define('MODULEPATH', BASEPATH .'Modules' .DS);

//
define('THEMEPATH', BASEPATH .'Themes' .DS);

//
define('SHAREDPATH', BASEPATH .'Shared' .DS);

//
define('NPDSPATH', BASEPATH .'npds' .DS);

//
require BASEPATH.'vendor'.DS.'autoload.php';

//
Debug::Reporting('all');
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); 

/** Log the Framework startup. */
Console::logSpeed('START Npds Framework');

/** Turn on output buffering. */
ob_start();

/** Load the application Constants. */
require APPPATH .'constants.php';

/** Load the application Configuration. */
require APPPATH .'config.php';

// Load the configuration files.
foreach (glob(BASEPATH .'Config/*.php') as $path) {
    $key = lcfirst(pathinfo($path, PATHINFO_FILENAME));

    Config::set($key, require($path));
}

/** Load the Route Filters. */
require APPPATH .'Routes/filters.php';

/** Set the current Timezone. */
date_default_timezone_set(Config::get('app.timezone'));

/** Initialize the Logger. */
Logger::initialize();

/** Set the Framework Exception and Error Handlers. */
set_exception_handler('Npds\Log\Logger::ExceptionHandler');
set_error_handler('Npds\Log\Logger::ErrorHandler');

// // Initialize the Aliases Loader.
AliasLoader::getInstance(Config::get('app.aliases', array()))->register();

/** A useful alias for the Query Builder Facade. */
class_alias('\Npds\Database\Query\Builder\Facade', 'Npds\QB');

/** Get the current Router instance. */
$router = Router::getInstance();

/** Load the application wide Routes. */
require APPPATH .'Routes/web/routes.php';
require APPPATH .'Routes/admin/routes.php';

// // Execute the local bootstrap.
require APPPATH .'Bootstrap.php';

/** Bootstrap the active Modules. */
Modules::bootstrap();

/** Bootstrap the active Packages. */
Packages::bootstrap();

/** Bootstrap the active Themes. */
Themes::bootstrap();

/** Initialize the Events Management. */
Events::initialize();

/** Initialize the Sessions. */
Session::initialize();

/** Execute the Request dispatching by Router. */
$router->dispatch();
