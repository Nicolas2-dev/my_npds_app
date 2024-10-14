<?php

use Npds\Routing\Router;


//
Router::get('admin', 'Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');
Router::get('admin.php', 'Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');

//Router::get('admin/(:any)', 'Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');

//
Router::get('admin/dashboard', 'Modules\Npds\Controllers\Admin\AdminDashboard@adminMain');