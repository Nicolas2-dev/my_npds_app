<?php

use Npds\Routing\Router;


// user main
Router::get('user', USER_FRONT_CONTROLLER.'UserMain@index');

// user Home
Router::get('user/edithome', USER_FRONT_CONTROLLER.'UserHome@edithome');
Router::post('user/savehome', USER_FRONT_CONTROLLER.'UserHome@savehome');


// login
Router::get('user/login', USER_FRONT_CONTROLLER.'UserLogin@index');
Router::post('user/submit', USER_FRONT_CONTROLLER.'UserLogin@submit');

// logout
Router::get('user/logout', USER_FRONT_CONTROLLER.'UserLogin@logout');



