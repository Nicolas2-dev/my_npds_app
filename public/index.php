<?php

// use Npds\Config\Config;

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
require BASEPATH .'Bootstrap'.DS.'Bootstrap.php';
