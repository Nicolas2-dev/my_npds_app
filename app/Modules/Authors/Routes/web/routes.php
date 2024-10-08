<?php

use Npds\Routing\Router;


// login
Router::get('admin/login', 'App\Modules\Authors\Controllers\Front\AuthorLogin@login');

//
Router::post('admin/login/submit', 'App\Modules\Authors\Controllers\Front\AuthorLogin@submit');

// logout
Router::get('admin/logout', 'App\Modules\Authors\Controllers\Front\AuthorLogin@logout');