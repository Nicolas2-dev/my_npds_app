<?php

/** Create alias for Router. */
use Npds\Routing\Router;

// Default Routing
Router::any('', 'App\Controllers\Welcome@welcome');

Router::any('index', 'App\Controllers\Welcome@welcome');

Router::any('subpage', 'App\Controllers\Welcome@subPage');

// All the un-matched Requests will be routed there.
//Router::catchAll('App\Controllers\Error@error404');

/** If no Route found and no Catch-All Route defined. */
Router::error('App\Controllers\Error@error404');
