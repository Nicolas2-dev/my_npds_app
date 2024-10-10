<?php

use Modules\Npds\Support\Secure;

if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {

    //
    define('NPDS_GRAB_GLOBALS_INCLUDED', 1);

    // Get values, slash, filter and extract
    Secure::request_query();

    // Post values, slash, filter and extract
    Secure::request_post();

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
