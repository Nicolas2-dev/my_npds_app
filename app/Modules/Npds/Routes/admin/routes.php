<?php

use Npds\Routing\Router;


//
Router::get('admin', 'App\Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');
Router::get('admin.php', 'App\Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');

Router::get('admin/(:any)', 'App\Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');

//
Router::get('admin/dashboard', 'App\Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');