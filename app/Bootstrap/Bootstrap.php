<?php


// Framework
use Npds\Log\Logger;
use Npds\Config\config;
use Npds\Routing\Router;
use Npds\Console\Console;
use Npds\Session\Session;
use Npds\Foundation\AliasLoader;
use Npds\Events\Manager as Events;
use App\Modules\Npds\Support\Debug;
use Npds\Modules\Manager as Modules;
use Npds\Themes\Manager as Themes;
use Npds\Packages\Manager as Packages;


/** Prepare the current directory for configuration files. */
// $configDir = dirname(__FILE__) .DS;

/** Check for valid configuration files. */
// if (! is_readable($configDir .'config.php') || ! is_readable($configDir .'constants.php')) {
//     die('No config.php or constants.php found, configure and rename *.example.php in ' .$configDir);
// }

// //
Debug::Reporting('all');
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); 

/** Log the Framework startup. */
Console::logSpeed('START Npds Framework');


/** Turn on output buffering. */
ob_start();


/** Load the application Constants. */
require APPPATH .'constants.php';

/** Load the System's helper functions. */
//require NPDSPATH .'functions.php';

/** Load the application Configuration. */
require APPPATH .'config.php';

// Load the configuration files.
foreach (glob(APPPATH .'Config/*.php') as $path) {
    $key = lcfirst(pathinfo($path, PATHINFO_FILENAME));

    Config::set($key, require($path));
}


/** Load the database Configuration. */
if (is_readable(APPPATH .'database.php')) {
    require APPPATH .'database.php';
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
AliasLoader::initialize();

/** A useful alias for the Query Builder Facade. */
class_alias('\Npds\Database\Query\Builder\Facade', 'Npds\QB');

/** Bootstrap the active Packages. */
Packages::bootstrap();

/** Get the current Router instance. */
$router = Router::getInstance();

/** Load the application wide Routes. */
require APPPATH .'Routes/web/routes.php';
require APPPATH .'Routes/admin/routes.php';

/** Bootstrap the active Modules. */
Modules::bootstrap();

/** Bootstrap the active Themes. */
Themes::bootstrap();

/** Initialize the Events Management. */
Events::initialize();

/** Initialize the Sessions. */
Session::initialize();

// // Execute the local Grabglobal.
if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {
    require APPPATH .'Bootstrap' .DS .'Grabglobals.php';
}

// dd(DB::table('config')->get());
// vd(Config::all());

// // Execute the local bootstrap.
require APPPATH .'Bootstrap.php';

/** Execute the Request dispatching by Router. */
$router->dispatch();
