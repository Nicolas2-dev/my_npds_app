<?php


use Npds\Routing\Route;


/** Define Route Filters. */

// A Testing Filter which dump the matched Route.
Route::filter('test', function($route) {
    echo '<pre>' .var_export($route, true) .'</pre>';
});
