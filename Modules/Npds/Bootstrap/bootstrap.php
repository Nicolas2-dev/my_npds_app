<?php

use Npds\Config\Config;
use Modules\Npds\Bootstrap\NpdsKernel;
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

// a revoir par la suite
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

// a supprimer par la suite
Session::session_manage();

// a supprimer par la suite
Language::make_tab_langue();

// a supprimer par la suite
Metalang::charg_metalang();
