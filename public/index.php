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
define('MODULEPATH', APPPATH .'Modules' .DS);

//
define('THEMEPATH', APPPATH .'Themes' .DS);

//
define('SHAREDPATH', APPPATH .'Shared' .DS);

//
define('NPDSPATH', BASEPATH .'npds' .DS);

//
require BASEPATH.'vendor'.DS.'autoload.php';

//
require APPPATH .'Bootstrap'.DS.'Bootstrap.php';


// echo BASEPATH.'<br />';
// echo WEBPATH.'<br />';
// echo APPPATH.'<br />';
// echo NPDSPATH.'<br />';
// echo MODULEPATH.'<br />';
// echo THEMEPATH.'<br />';

// echo '<br />';

// vd(Config::all());

// echo 'good !!!';