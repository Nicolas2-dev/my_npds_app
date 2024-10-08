<?php

use Npds\Routing\Url;
use Npds\Routing\Route;
use Npds\Session\Session;
use App\Modules\Npds\Support\Facades\Auth;

// 
Route::filter('check.user.login', function($route) {
    if (Auth::guard('user')) {
        //Session::set('message', ['type' => 'warning', 'text' => __d('users', 'Error : Vous ete deja conecter a votre compte.')]);

        return Url::redirect('user');
    }
});

// 
Route::filter('check.user.logout', function($route) {
    if (!Auth::guard('user')) {
        Session::set('message', ['type' => 'info', 'text' => __d('users', 'Veullez-vous coneté a votre compte.')]);
        
        return Url::redirect('user/login');
    }
});
