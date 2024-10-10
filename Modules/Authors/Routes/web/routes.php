<?php

use Npds\Routing\Router;


// login
Router::get('admin/login', 'Modules\Authors\Controllers\Front\AuthorLogin@login');

//
Router::post('admin/login/submit', 'Modules\Authors\Controllers\Front\AuthorLogin@submit');

// logout
Router::get('admin/logout', 'Modules\Authors\Controllers\Front\AuthorLogout@logout');