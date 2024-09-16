<?php


/** Create alias for Router. */
use Npds\Routing\Router;

/** Define static routes. */

// Default Routing
Router::any('', 'App\Controllers\Welcome@welcome');

Router::any('index', 'App\Controllers\Welcome@welcome');

Router::any('subpage', 'App\Controllers\Welcome@subPage');




// All the un-matched Requests will be routed there.
//Router::catchAll('App\Controllers\Error@error404');

/** If no Route found and no Catch-All Route defined. */
Router::error('App\Controllers\Error@error404');




// use Npds\Net\Router;

// //
// // The global parameter patterns.

// Router::pattern('slug', '.*');

// Router::get('base',        'App\Controllers\Sample@index');


// Router::get('/',        'App\Controllers\Front\FrontStartpage@select_start_page');
// Router::get('index/{op?}',        'App\Controllers\Front\FrontStartpage@select_start_page');


// Router::get('stat',    'App\Controllers\Front\FrontStats@index');

// Router::get('error',    'App\Controllers\Sample@error');
// Router::get('redirect', 'App\Controllers\Sample@redirect');
// Router::get('request',  'App\Controllers\Sample@request');

// Router::get('pages/{page?}', 'App\Controllers\Sample@page');
// //Route::get('pages/(:any?)', 'App\Controllers\Sample@page');

// Router::get('blog/{slug?}', 'App\Controllers\Sample@post');
// //Route::get('blog/(:all)', 'App\Controllers\Sample@post');


// // A route executing a closure.
// Router::get('test', function ()
// {
//     echo 'This is a test.<br>';
// });

// // A route executing a closure and having own parameter patterns.
// Router::get('language/{code?}', array('uses' => function ($code)
// {
//     echo htmlentities($code);

// }, 'where' => array('code' => '[a-z]{2}')));
