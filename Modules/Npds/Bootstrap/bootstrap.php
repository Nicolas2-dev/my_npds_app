<?php

use Npds\Config\Config;
use Modules\Npds\Bootstrap\NpdsKernel;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Npds\Support\Facades\Session;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;

NpdsKernel::aliases_loader();

$configDir = dirname(dirname(__FILE__)) .DS;

//
include $configDir .'constants.php';

include $configDir .'Support/helpers.php';

include $configDir .'Support/Metalang.php';

include $configDir .'Boxe/Boxe.php';

include $configDir .'Events/events.php';

include $configDir .'Routes/web/routes.php';

include $configDir .'Routes/api/routes.php';

include $configDir .'Routes/admin/routes.php';

// a supprimer par la suite
if (Config::get('npds.mysql_i') == 1) {
    include(module_path('Npds/Library/Database/mysqli.php'));
} else {
    include(module_path('Npds/Library/Database/mysql.php'));
}

// a supprimer par la suite
$dblink = Mysql_Connexion();

// Antie Spam
Spam::spam_boot();

// Multi-language
if (isset($choice_user_language)) {
    if ($choice_user_language != '') {

        $user_cook_duration = Config::get('npds.user_cook_duration');

        if ($user_cook_duration <= 0) {
            $user_cook_duration = 1;
        }

        $timeX = time() + (3600 * $user_cook_duration);

        if ((stristr(Language::cache_list(), $choice_user_language)) and ($choice_user_language != ' ')) {
            Cookie::set('user_language', $choice_user_language, $timeX);
            
            $user_language = $choice_user_language;
        }
    }
}

if (Config::get('npds.multi_langue')) {
    if (($user_language != '') and ($user_language != " ")) {

        $tmpML = stristr(Language::cache_list(), $user_language);
        $tmpML = explode(' ', $tmpML);

        if ($tmpML[0]) {
            Config::set('npds.language', $tmpML[0]);
        }
    }
}
// Multi-language

// Init Sesion
Session::session_manage();

// Init Tab Language
Language::make_tab_langue();

// Init Metalang
Metalang::charg_metalang();
