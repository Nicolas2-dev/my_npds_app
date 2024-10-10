<?php

use Npds\Config\Config;
use Modules\Npds\Support\Secure;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Cookie;

// a revoir par la suite
if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {

    //
    define('NPDS_GRAB_GLOBALS_INCLUDED', 1);

    //  
    Spam::spam_boot();

    // include current charset
    if (file_exists(module_path('Npds/storage/meta/cur_charset.php'))) {
        include(module_path('Npds/storage/meta/cur_charset.php'));
    }

    // Get values, slash, filter and extract
    if (!empty($_GET)) {

        array_walk_recursive($_GET, [Sanitize::class, 'addslashes_GPC']);
        reset($_GET); 

        array_walk_recursive($_GET, [Secure::class, 'url']);
        extract($_GET, EXTR_OVERWRITE);
    }

    //
    if (!empty($_POST)) {
        array_walk_recursive($_POST, [Sanitize::class, 'addslashes_GPC']);
        
        array_walk_recursive($_POST, [Secure::class, 'post']);

        extract($_POST, EXTR_OVERWRITE);
    }

    // Cookies - analyse et purge - shiney 07-11-2010
    if (!empty($_COOKIE)) {
        extract($_COOKIE, EXTR_OVERWRITE);
    }

    //
    if (isset($user)) {
        $ibid = explode(':', base64_decode($user));
        array_walk($ibid, [Secure::class, 'url']);
        $user = base64_encode(str_replace("%3A", ":", urlencode(base64_decode($user))));
    }

    //
    if (isset($user_language)) {
        $ibid = explode(':', $user_language);
        array_walk($ibid, [Secure::class, 'url']);
        $user_language = str_replace("%3A", ":", urlencode($user_language));
    }

    //
    if (isset($admin)) {
        $ibid = explode(':', base64_decode($admin));
        array_walk($ibid, [Secure::class, 'url']);
        $admin = base64_encode(str_replace('%3A', ':', urlencode(base64_decode($admin))));
    }

    // Cookies - analyse et purge - shiney 07-11-2010
    if (!empty($_SERVER)) {
        extract($_SERVER, EXTR_OVERWRITE);
    }

    //
    if (!empty($_ENV)) {
        extract($_ENV, EXTR_OVERWRITE);
    }

    //
    if (!empty($_FILES)) {
        foreach ($_FILES as $key => $value) {
            $$key = $value['tmp_name'];
        }
    }

}

// a supprimer par la suite
if (Config::get('npds.mysql_i') == 1) {
    include(module_path('Npds/Library/Database/mysqli.php'));
} else {
    include(module_path('Npds/Library/Database/mysql.php'));
}

// a supprimer par la suite
$dblink = Mysql_Connexion();

// a supprimer par la suite
if (isset($user)) {
    $cookie = Cookie::cookiedecode($user);
}
