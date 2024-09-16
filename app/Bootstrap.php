<?php

use Npds\QB;
use Npds\Config\Config;
use Npds\Support\Facades\DB;

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
$mainfile = 1;

//
if (isset($user)) {
    $cookie = cookiedecode($user);
}

//
session_manage();

//
make_tab_langue();

//
charg_metalang();

// auth.php

$rowQ1 = Q_Select("SELECT * FROM config", 3600);
if ($rowQ1) {
    foreach ($rowQ1[0] as $key => $value) {
        $$key = $value;
    }

    $upload_table =  $upload_table;
}

settype($forum, 'integer');

if ($allow_upload_forum) {
    $rowQ1 = Q_Select("SELECT attachement FROM forums WHERE forum_id='$forum'", 3600);

    if ($rowQ1) {
        foreach ($rowQ1[0] as $value) {
            $allow_upload_forum = $value;
        }
    }
}

$rowQ1 = Q_Select("SELECT forum_pass FROM forums WHERE forum_id='$forum' AND forum_type='1'", 3600);
if ($rowQ1) {
    if (isset($Forum_Priv[$forum])) {
        $Xpasswd = base64_decode($Forum_Priv[$forum]);

        foreach ($rowQ1[0] as $value) {
            $forum_xpass = $value;
        }

        if (md5($forum_xpass) == $Xpasswd)
            $Forum_passwd = $forum_xpass;
        else
            setcookie("Forum_Priv[$forum]", '', 0);
    } else {
        if (isset($Forum_passwd)) {
            foreach ($rowQ1[0] as $value) {
                if ($value == $Forum_passwd)
                    setcookie("Forum_Priv[$forum]", base64_encode(md5($Forum_passwd)), time() + 900);
            }
        }
    }
}
