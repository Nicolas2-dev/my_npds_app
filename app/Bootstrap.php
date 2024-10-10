<?php

use Npds\Config\Config;
use Modules\Npds\Library\CookieManager;
use Modules\Npds\Library\SessionManager;
use Modules\Npds\Library\LanguageManager;
use Modules\Npds\Library\MetalangManager;

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
