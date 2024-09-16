<?php

use Npds\Routing\Router;


// user main
Router::get('user', 'App\Modules\Users\Controllers\Front\UserMain@index');

// user Home
Router::get('user/edithome', 'App\Modules\Users\Controllers\Front\UserHome@edithome');
Router::post('user/savehome', 'App\Modules\Users\Controllers\Front\UserHome@savehome');


// login
Router::get('user/login', 'App\Modules\Users\Controllers\Front\UserLogin@index');
Router::post('user/submit', 'App\Modules\Users\Controllers\Front\UserLogin@submit');

// logout
Router::get('user/logout', 'App\Modules\Users\Controllers\Front\UserLogin@logout');



