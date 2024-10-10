<?php

use Npds\Config\Config;
use Modules\Npds\Library\CookieManager;
use Modules\Npds\Library\SessionManager;
use Modules\Npds\Library\LanguageManager;
use Modules\Npds\Library\MetalangManager;
use Modules\Npds\Support\Secure;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Library\SpamManager;


if (!defined('NPDS_GRAB_GLOBALS_INCLUDED')) {

    //
    define('NPDS_GRAB_GLOBALS_INCLUDED', 1);

    //  
    SpamManager::getInstance()->spam_boot();

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

// Multi-language
// a integrer nouvel version language

// Multi-language
// $local_path='';

// settype($user_language,'string');

// if (isset($module_mark))
//    $local_path='../../';

// if (file_exists($local_path.'cache/language.php'))
//    include ($local_path.'cache/language.php');
// else
//    include ($local_path.'manuels/list.php');

global $languageslist;

$languageslist = "chinese english french german spanish";

if (isset($choice_user_language)) {
    if ($choice_user_language != '') {

        $user_cook_duration = Config::get('npds.user_cook_duration');

        if ($user_cook_duration <= 0) {
            $user_cook_duration = 1;
        }

        $timeX = time() + (3600 * $user_cook_duration);

        if ((stristr($languageslist, $choice_user_language)) and ($choice_user_language != ' ')) {
            setcookie('user_language', $choice_user_language, $timeX);
            $user_language = $choice_user_language;
        }
    }
}

if (Config::get('npds.multi_langue')) {
    if (($user_language != '') and ($user_language != " ")) {

        $tmpML = stristr($languageslist, $user_language);

        $tmpML = explode(' ', $tmpML);

        if ($tmpML[0]) {
            Config::set('npds.language', $tmpML[0]);
        }
    }
}
// Multi-language

//
if (Config::get('npds.mysql_i') == 1) {
    include(module_path('Npds/Library/Database/mysqli.php'));
} else {
    include(module_path('Npds/Library/Database/mysql.php'));
}

//
$dblink = Mysql_Connexion();

//
// $mainfile = 1;

//
if (isset($user)) {
    $cookie = CookieManager::getInstance()->cookiedecode($user);
}

//
SessionManager::getInstance()->session_manage();

//
LanguageManager::getInstance()->make_tab_langue();

//
MetalangManager::getInstance()->charg_metalang();
