<?php

use Npds\Http\Request;
use Modules\Npds\Support\Error;
use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Support\Sanitize;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Library\JsManager;
use Modules\Npds\Library\CssManager;
use Modules\Npds\Library\LogManager;
use Modules\Npds\Library\UrlManager;
use Modules\npds\Library\AuthManager;
use Modules\Npds\Library\CodeManager;
use Modules\Npds\Library\DateManager;
use Modules\Npds\Library\HackManager;
use Modules\Npds\Library\SpamManager;
use Modules\Npds\Library\CryptManager;
use Modules\Npds\Library\MediaManager;
use Modules\Npds\Library\PixelManager;
use Modules\Npds\Library\CookieManager;
use Modules\Npds\Library\MailerManager;
use Modules\Npds\Library\SessionManager;
use Modules\Npds\Library\LanguageManager;
use Modules\Npds\Library\MetalangManager;
use Modules\Npds\Library\PasswordManager;
use Modules\Npds\Library\EmoticoneManager;
use Modules\Npds\Library\PaginatorManager;
use Modules\Npds\Library\SubscribeManager;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Authors\Library\AuthorsManager;

// SuperCache

if (! function_exists('CacheEmpty'))
{
    /**
     * [CacheEmpty description]
     *
     * @return  [type]  [return description]
     */
    function CacheEmpty()
    {
        return new SuperCacheEmpty();
    }
}

if (! function_exists('CacheManager'))
{
    /**
     * [CacheManager description]
     *
     * @return  [type]  [return description]
     */
    function CacheManager()
    {
        return new SuperCacheManager();
    }
}

// if (! function_exists('Q_Select'))
// {
//     /**
//      * [Q_Select description]
//      *
//      * @param   [type]  $Xquery     [$Xquery description]
//      * @param   [type]  $retention  [$retention description]
//      *
//      * @return  [type]              [return description]
//      */
//     function Q_Select($Xquery, $retention = 3600)
//     {
//         global $SuperCache, $cache_obj;

//         if (($SuperCache) and ($cache_obj)) {
//             $row = $cache_obj->CachingQuery($Xquery, $retention);

//             return $row;
//         } else {
//             $result = @sql_query($Xquery);
//             $tab_tmp = array();

//             while ($row = sql_fetch_assoc($result)) {
//                 $tab_tmp[] = $row;
//             }

//             return $tab_tmp;
//         }
//     }
// }

// if (! function_exists('DBQ_Select'))
// {
//     /**
//      * [Q_Select description]
//      *
//      * @param   [type]  $Xquery     [$Xquery description]
//      * @param   [type]  $retention  [$retention description]
//      *
//      * @return  [type]              [return description]
//      */
//     function DBQ_Select($Xquery, $retention = 3600)
//     {
//         global $SuperCache, $cache_obj;

//         if (($SuperCache) and ($cache_obj)) {
//             $row = $cache_obj->CachingQuery($Xquery, $retention);

//             return $row;
//         } else {
//             // $tab_tmp = array();

//             // foreach($Xquery as $query)
//             // {
//             //     $tab_tmp[] = $query;
//             // }

//             // return $tab_tmp;
//             return $Xquery;
//         }
//     }
// }

// if (! function_exists('PG_clean'))
// {
//     function PG_clean($request)
//     {
//         global $CACHE_CONFIG;

//         $page = md5($request);
//         $dh = opendir($CACHE_CONFIG['data_dir']);

//         while (false !== ($filename = readdir($dh))) {
//             if ($filename === '.' 
//             or $filename === '..' 
//             or (strpos($filename, $page) === FALSE)) {
//                 continue;
//             }

//             unlink($CACHE_CONFIG['data_dir'] . $filename);
//         }

//         closedir($dh);
//     }
// }

// if (! function_exists('Q_Clean'))
// {
//     /**
//      * [Q_Clean description]
//      *
//      * @return  [type]  [return description]
//      */
//     function Q_Clean()
//     {
//         global $CACHE_CONFIG;

//         $dh = opendir($CACHE_CONFIG['data_dir'] . "sql");

//         while (false !== ($filename = readdir($dh))) {
//             if ($filename === '.' 
//             or $filename === '..') {
//                 continue;
//             }

//             if (is_file($CACHE_CONFIG['data_dir'] . "sql/" . $filename)) {
//                 unlink($CACHE_CONFIG['data_dir'] . "sql/" . $filename);
//             }
//         }

//         closedir($dh);

//         $fp = fopen($CACHE_CONFIG['data_dir'] . "sql/.htaccess", 'w');
//         @fputs($fp, "Deny from All");
//         fclose($fp);
//     }
// }

// if (! function_exists('SC_clean'))
// {
//     /**
//      * [SC_clean description]
//      *
//      * @return  [type]  [return description]
//      */
//     function SC_clean()
//     {
//         global $CACHE_CONFIG;

//         $dh = opendir($CACHE_CONFIG['data_dir']);

//         while (false !== ($filename = readdir($dh))) {
//             if ($filename === '.' 
//             or $filename === '..' 
//             or $filename === 'ultramode.txt' 
//             or $filename === 'net2zone.txt' 
//             or $filename === 'sql' 
//             or $filename === 'index.html') {
//                 continue;
//             }

//             if (is_file($CACHE_CONFIG['data_dir'] . $filename)) {
//                 unlink($CACHE_CONFIG['data_dir'] . $filename);
//             }
//         }
        
//         closedir($dh);
//         Q_Clean();
//     }
// }

// if (! function_exists('SC_infos'))
// {
//     /**
//      * [SC_infos description]
//      *
//      * @return  [type]  [return description]
//      */
//     function SC_infos()
//     {
//         global $SuperCache, $App_sc;

//         $infos = '';

//         if ($SuperCache) {
//             /*
//             $infos = $App_sc ? '<span class="small">'.__d('npds', '.:Page >> Super-Cache:.').'</span>':'';
//             */

//             if ($App_sc) {
//                 $infos = '<span class="small">' . __d('npds', '.:Page >> Super-Cache:.') . '</span>';
//             } else {
//                 $infos = '<span class="small">' . __d('npds', '.:Page >> Super-Cache:.') . '</span>';
//             }
//         }

//         return $infos;
//     }
// }

// Security

if (! function_exists('removeHack'))
{
    /**
     * [removeHack description]
     *
     * @return  [type]  [return description]
     */
    function removeHack($Xstring)
    {
        return HackManager::getInstance()->remove($Xstring);
    }
}

// Crypt

if (! function_exists('keyED'))
{
    /**
     * [keyED description]
     *
     * @param   [type]  $txt          [$txt description]
     * @param   [type]  $encrypt_key  [$encrypt_key description]
     *
     * @return  [type]                [return description]
     */
    function keyED($txt, $encrypt_key)
    {
        return CryptManager::getInstance()->keyED($txt, $encrypt_key);
    }
}

if (! function_exists('encrypt'))
{
    /**
     * [encrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    function encrypt($txt)
    {
        return CryptManager::getInstance()->encrypt($txt);
    }
}

if (! function_exists('encryptK'))
{
    /**
     * [encryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    function encryptK($txt, $C_key)
    {
        return CryptManager::getInstance()->encryptK($txt, $C_key);
    }
}

if (! function_exists('decrypt'))
{
    /**
     * [decrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    function decrypt($txt)
    {
        return CryptManager::getInstance()->decrypt($txt);
    }
}

if (! function_exists('decryptK'))
{
    /**
     * [decryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    function decryptK($txt, $C_key)
    {
        return CryptManager::getInstance()->decryptK($txt, $C_key);
    }
}

// Sanitize

if (! function_exists('changetoamp'))
{
    /**
     * [changetoamp description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    function changetoamp($r)
    {
        return Sanitize::changetoamp($r);
    }
}

if (! function_exists('changetoampadm'))
{
    /**
     * [changetoampadm description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    function changetoampadm($r)
    {
        return Sanitize::changetoampadm($r);
    }
}

if (! function_exists('conv2br'))
{
    /**
     * [conv2br description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    function conv2br($txt)
    {
        return Sanitize::conv2br($txt);
    }
}

if (! function_exists('hexfromchr'))
{
    /**
     * [hexfromchr description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    function hexfromchr($txt)
    {
        return Sanitize::hexfromchr($txt);
    }
}

if (! function_exists('utf8_java'))
{
    /**
     * [utf8_java description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function utf8_java($ibid)
    {
        return Sanitize::utf8_java($ibid);
    }
}

if (! function_exists('wrh'))
{
    /**
     * [wrh description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function wrh($ibid)
    {
        return Sanitize::wrh($ibid);
    }
}

if (! function_exists('FixQuotes'))
{
    /**
     * [FixQuotes description]
     *
     * @param   [type]  $what  [$what description]
     *
     * @return  [type]         [return description]
     */
    function FixQuotes($what = '')
    {
        return Sanitize::FixQuotes($what = '');
    }
}

if (! function_exists('split_string_without_space'))
{
    /**
     * [split_string_without_space description]
     *
     * @param   [type]  $msg    [$msg description]
     * @param   [type]  $split  [$split description]
     *
     * @return  [type]          [return description]
     */
    function split_string_without_space($msg, $split)
    {
        return Sanitize::split_string_without_space($msg, $split);
    }
}

if (! function_exists('wrapper_f'))
{
    /**
     * [wrapper_f description]
     *
     * @param   [type]  $string  [$string description]
     * @param   [type]  $key     [$key description]
     * @param   [type]  $cols    [$cols description]
     *
     * @return  [type]           [return description]
     */
    function wrapper_f(&$string, $key, $cols)
    {
        return Sanitize::wrapper_f($string, $key, $cols);
    }
}

if (! function_exists('make_clickable'))
{
    /**
     * [make_clickable description]
     *
     * @param   [type]  $text  [$text description]
     *
     * @return  [type]         [return description]
     */
    function make_clickable($text)
    {
        return Sanitize::make_clickable($text);
    }
}

if (! function_exists('undo_htmlspecialchars'))
{
    /**
     * [undo_htmlspecialchars description]
     *
     * @param   [type]  $input  [$input description]
     *
     * @return  [type]          [return description]
     */
    function undo_htmlspecialchars($input)
    {
        return Sanitize::undo_htmlspecialchars($input);
    }
}

// Session

if (! function_exists('session_manage'))
{

    function session_manage()
    {
        return SessionManager::getInstance()->session_manage();
    }
}

// Date

if (! function_exists('NightDay'))
{

    function NightDay()
    {
        return DateManager::getInstance()->NightDay();
    }
}

if (! function_exists('formatTimestamp'))
{

    function formatTimestamp($time)
    {
        return DateManager::getInstance()->formatTimestamp($time);
    }
}

if (! function_exists('convertdateTOtimestamp'))
{
    /**
     * [convertdateTOtimestamp description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    function convertdateTOtimestamp($myrow)
    {
        return DateManager::getInstance()->convertdateTOtimestamp($myrow);
    }
}

if (! function_exists('post_convertdate'))
{
    /**
     * [post_convertdate description]
     *
     * @param   [type]  $tmst  [$tmst description]
     *
     * @return  [type]         [return description]
     */
    function post_convertdate($tmst)
    {
        return DateManager::getInstance()->post_convertdate($tmst);
    }
}

if (! function_exists('convertdate'))
{
    /**
     * [convertdate description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    function convertdate($myrow)
    {
        return DateManager::getInstance()->convertdate($myrow);
    }
}

// Language

if (! function_exists('aff_langue'))
{
    /**
     * [aff_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function aff_langue($ibid)
    {
        return LanguageManager::getInstance()->aff_langue($ibid);
    }
}

if (! function_exists('make_tab_langue'))
{
    /**
     * [make_tab_langue description]
     *
     * @return  [type]  [return description]
     */
    function make_tab_langue()
    {
        return LanguageManager::getInstance()->make_tab_langue();
    }
}

if (! function_exists('aff_localzone_langue'))
{
    /**
     * [aff_localzone_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function aff_localzone_langue($ibid)
    {
        return LanguageManager::getInstance()->aff_localzone_langue($ibid);
    }
}

if (! function_exists('aff_local_langue'))
{
    /**
     * [aff_local_langue description]
     *
     * @param   [type]  $ibid_index  [$ibid_index description]
     * @param   [type]  $ibid        [$ibid description]
     * @param   [type]  $mess        [$mess description]
     *
     * @return  [type]               [return description]
     */
    function aff_local_langue($ibid_index, $ibid, $mess = '')
    {
        return LanguageManager::getInstance()->aff_local_langue($ibid_index, $ibid, $mess = '');
    }
}

if (! function_exists('preview_local_langue'))
{
    /**
     * [preview_local_langue description]
     *
     * @param   [type]  $local_user_language  [$local_user_language description]
     * @param   [type]  $ibid                 [$ibid description]
     *
     * @return  [type]                        [return description]
     */
    function preview_local_langue($local_user_language, $ibid)
    {
        return LanguageManager::getInstance()->preview_local_langue($local_user_language, $ibid);
    }
}

if (! function_exists('language_iso'))
{
    /**
     * [language_iso description]
     *
     * @param   [type]  $l  [$l description]
     * @param   [type]  $s  [$s description]
     * @param   [type]  $c  [$c description]
     *
     * @return  [type]      [return description]
     */
    function language_iso($l, $s, $c)
    {
        return LanguageManager::getInstance()->language_iso($l, $s, $c);
    }
}

// Request

if (! function_exists('getip'))
{
    /**
     * [getip description]
     *
     * @return  [type]  [return description]
     */
    function getip()
    {
        return Request::getip();
    }
}

// Logs

if (! function_exists('Ecr_Log'))
{
    /**
     * [Ecr_Log description]
     *
     * @param   [type]  $fic_log  [$fic_log description]
     * @param   [type]  $req_log  [$req_log description]
     * @param   [type]  $mot_log  [$mot_log description]
     *
     * @return  [type]            [return description]
     */
    function Ecr_Log($fic_log, $req_log, $mot_log)
    {
        return LogManager::getInstance()->Ecr_Log($fic_log, $req_log, $mot_log);
    }
}

// Mailer

if (! function_exists('send_email'))
{
    /**
     * [send_email description]
     *
     * @param   [type] $email     [$email description]
     * @param   [type] $subject   [$subject description]
     * @param   [type] $message   [$message description]
     * @param   [type] $from      [$from description]
     * @param   [type] $priority  [$priority description]
     * @param   false  $mime      [$mime description]
     * @param   text   $file      [$file description]
     *
     * @return  [type]            [return description]
     */
    function send_email($email, $subject, $message, $from = "", $priority = false, $mime = "text", $file = null)
    {
        return MailerManager::getInstance()->send_email($email, $subject, $message, $from = "", $priority = false, $mime = "text", $file = null);
    }
}

if (! function_exists('copy_to_email'))
{
    /**
     * [copy_to_email description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     * @param   [type]  $sujet      [$sujet description]
     * @param   [type]  $message    [$message description]
     *
     * @return  [type]              [return description]
     */
    function copy_to_email($to_userid, $sujet, $message)
    {
        return MailerManager::getInstance()->copy_to_email($to_userid, $sujet, $message);
    }
}

if (! function_exists('fakedmail'))
{
    /**
     * [fakedmail description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    function fakedmail($r)
    {
        return MailerManager::getInstance()->fakedmail($r);;
    }
}

if (! function_exists('checkdnsmail'))
{
    /**
     * [checkdnsmail description]
     *
     * @param   [type]  $email  [$email description]
     *
     * @return  [type]          [return description]
     */
    function checkdnsmail($email)
    {
        return MailerManager::getInstance()->checkdnsmail($email);
    }
}

if (! function_exists('isbadmailuser'))
{
    /**
     * [isbadmailuser description]
     *
     * @param   [type]  $utilisateur  [$utilisateur description]
     *
     * @return  [type]                [return description]
     */
    function isbadmailuser($utilisateur)
    {
        return MailerManager::getInstance()->isbadmailuser($utilisateur);
    }
}

// Metalang

if (! function_exists('arg_filter'))
{
    /**
     * [arg_filter description]
     *
     * @param   [type]  $arg  [$arg description]
     *
     * @return  [type]        [return description]
     */
    function arg_filter($arg)
    {
        return MetalangManager::getInstance()->arg_filter($arg);
    }
}

// if (! function_exists('MM_img'))
// {
//     /**
//      * [MM_img description]
//      *
//      * @param   [type]  $ibid  [$ibid description]
//      *
//      * @return  [type]         [return description]
//      */
//     function MM_img($ibid)
//     {
//         return MetalangManager::getInstance()->MM_img($ibid);
//     }
// }

if (! function_exists('charg'))
{
    /**
     * [charg description]
     *
     * @param   [type]  $funct      [$funct description]
     * @param   [type]  $arguments  [$arguments description]
     *
     * @return  [type]              [return description]
     */
    function charg($funct, $arguments)
    {
        return MetalangManager::getInstance()->charg($funct, $arguments);
    }
}


if (! function_exists('match_uri'))
{
    /**
     * [match_uri description]
     *
     * @param   [type]  $racine  [$racine description]
     * @param   [type]  $R_uri   [$R_uri description]
     *
     * @return  [type]           [return description]
     */
    function match_uri($racine, $R_uri)
    {
        return MetalangManager::getInstance()->match_uri($racine, $R_uri);
    }
}

if (! function_exists('charg_metalang'))
{
    /**
     * [charg_metalang description]
     *
     * @return  [type]  [return description]
     */
    function charg_metalang()
    {
        return MetalangManager::getInstance()->charg_metalang();
    }
}

if (! function_exists('ana_args($arg)'))
{
    /**
     * [ana_args description]
     *
     * @param   [type]  $arg  [$arg description]
     *
     * @return  [type]        [return description]
     */
    function ana_args($arg)
    {
        return MetalangManager::getInstance()->ana_args($arg);
    }
}

if (! function_exists('meta_lang'))
{
    /**
     * [meta_lang description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    function meta_lang($Xcontent)
    {
        return Metalang::meta_lang($Xcontent);
    }
}

// Url

if (! function_exists('redirect_url'))
{
    
    function redirect_url($urlx)
    {
        return UrlManager::getInstance()->redirect($urlx);
    }
}

if (! function_exists('redirect_time'))
{
    
    function redirect_time($urlx, $time)
    {
        return UrlManager::getInstance()->redirect_time($urlx, $time);
    }
}

// Pixel

if (! function_exists('dataimagetofileurl'))
{
    /**
     * [dataimagetofileurl description]
     *
     * @param   [type]  $base_64_string  [$base_64_string description]
     * @param   [type]  $output_path     [$output_path description]
     *
     * @return  [type]                   [return description]
     */
    function dataimagetofileurl($base_64_string, $output_path)
    {
        return PixelManager::getInstance()->dataimagetofileurl($base_64_string, $output_path);
    }
}

// Emoticone

if (! function_exists('smilie'))
{
    /**
     * [smilie description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    function smilie($message)
    {
        return EmoticoneManager::getInstance()->smilie($message);
    }
}

if (! function_exists('smile'))
{
    /**
     * [smile description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    function smile($message)
    {
        return EmoticoneManager::getInstance()->smile($message);
    }
}

if (! function_exists('putitems_more'))
{
    /**
     * [putitems_more description]
     *
     * @return  [type]  [return description]
     */
    function putitems_more()
    {
        return EmoticoneManager::getInstance()->putitems_more();
    }
}

if (! function_exists('putitems'))
{
    /**
     * [putitems description]
     *
     * @param   [type]  $targetarea  [$targetarea description]
     *
     * @return  [type]               [return description]
     */
    function putitems($targetarea)
    {
        return EmoticoneManager::getInstance()->putitems($targetarea);
    }
}

// Media

if (! function_exists('aff_video_yt'))
{
    /**
     * [aff_video_yt description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function aff_video_yt($ibid)
    {
        return MediaManager::getInstance()->aff_video_yt($ibid);
    }
}

// Password

if (! function_exists('getOptimalBcryptCostParameter'))
{
    /**
     * [getOptimalBcryptCostParameter description]
     *
     * @param   [type]  $pass       [$pass description]
     * @param   [type]  $AlgoCrypt  [$AlgoCrypt description]
     * @param   [type]  $min_ms     [$min_ms description]
     *
     * @return  [type]              [return description]
     */
    function getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms = 100)
    {
        return PasswordManager::getInstance()->getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms = 100);
    }
}

// Assets

if (! function_exists('import_css_javascript'))
{
    /**
     * [import_css_javascript description]
     *
     * @param   [type]  $tmp_theme      [$tmp_theme description]
     * @param   [type]  $language       [$language description]
     * @param   [type]  $fw_css         [$fw_css description]
     * @param   [type]  $css_pages_ref  [$css_pages_ref description]
     * @param   [type]  $css            [$css description]
     *
     * @return  [type]                  [return description]
     */
    function import_css_javascript($tmp_theme, $fw_css, $css_pages_ref = '', $css = '')
    {
        return CssManager::getInstance()->import_css_javascript($tmp_theme, $fw_css, $css_pages_ref, $css);
    }
}

if (! function_exists('import_css'))
{
    /**
     * [import_css description]
     *
     * @param   [type]  $tmp_theme      [$tmp_theme description]
     * @param   [type]  $language       [$language description]
     * @param   [type]  $fw_css         [$fw_css description]
     * @param   [type]  $css_pages_ref  [$css_pages_ref description]
     * @param   [type]  $css            [$css description]
     *
     * @return  [type]                  [return description]
     */
    function import_css($tmp_theme, $fw_css, $css_pages_ref, $css)
    {
        return CssManager::getInstance()->import_css($tmp_theme, $fw_css, $css_pages_ref, $css);
    }
}

if (! function_exists('adminfoot'))
{
    /**
     * [adminfoot description]
     *
     * @param   [type]  $fv             [$fv description]
     * @param   [type]  $fv_parametres  [$fv_parametres description]
     * @param   [type]  $arg1           [$arg1 description]
     * @param   [type]  $foo            [$foo description]
     *
     * @return  [type]                  [return description]
     */
    function adminfoot($fv, $fv_parametres, $arg1, $foo)
    {
        return CssManager::getInstance()->adminfoot($fv, $fv_parametres, $arg1, $foo);
    }
}

if (! function_exists('auto_complete'))
{
    /**
     * [auto_complete description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $temps_cache   [$temps_cache description]
     *
     * @return  [type]                 [return description]
     */
    function auto_complete($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache)
    {
        return JsManager::getInstance()->auto_complete($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache);
    }
}

if (! function_exists('auto_complete_multi'))
{
    /**
     * [auto_complete_multi description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $req           [$req description]
     *
     * @return  [type]                 [return description]
     */
    function auto_complete_multi($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req)
    {
        return JsManager::getInstance()->auto_complete_multi($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req);
    }
}

// Spam

if (! function_exists('preg_anti_spam'))
{
    /**
     * [preg_anti_spam description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function preg_anti_spam($ibid)
    {
        return SpamManager::getInstance()->preg_anti_spam($ibid);;
    }
}

if (! function_exists('anti_spam'))
{
    /**
     * [anti_spam description]
     *
     * @param   [type]  $str       [$str description]
     * @param   [type]  $highcode  [$highcode description]
     *
     * @return  [type]             [return description]
     */
    function anti_spam($str, $highcode = 0)
    {
        return SpamManager::getInstance()->anti_spam($str, $highcode);
    }
}

if (! function_exists('Q_spambot'))
{
    /**
     * [Q_spambot description]
     *
     * @return  [type]  [return description]
     */
    function Q_spambot()
    {
        return SpamManager::getInstance()->Q_spambot();
    }
}

if (! function_exists('L_spambot'))
{
    /**
     * [L_spambot description]
     *
     * @param   [type]  $ip      [$ip description]
     * @param   [type]  $status  [$status description]
     *
     * @return  [type]           [return description]
     */
    function L_spambot($ip, $status)
    {
        return SpamManager::getInstance()->L_spambot($ip, $status);
    }
}

if (! function_exists('R_spambot'))
{
    /**
     * [R_spambot description]
     *
     * @param   [type]  $asb_question  [$asb_question description]
     * @param   [type]  $asb_reponse   [$asb_reponse description]
     * @param   [type]  $message       [$message description]
     *
     * @return  [type]                 [return description]
     */
    function R_spambot($asb_question, $asb_reponse, $message = '')
    {
        return SpamManager::getInstance()->R_spambot($asb_question, $asb_reponse, $message = '');
    }
}

if (! function_exists('spam_boot'))
{
    /**
     * [spam_boot description]
     *
     * @return  [type]  [return description]
     */
    function spam_boot()
    {
        return SpamManager::getInstance()->spam_boot();
    }
}


// Subscribe

if (! function_exists('subscribe_mail'))
{
    /**
     * [subscribe_mail description]
     *
     * @param   [type]  $Xtype    [$Xtype description]
     * @param   [type]  $Xtopic   [$Xtopic description]
     * @param   [type]  $Xforum   [$Xforum description]
     * @param   [type]  $Xresume  [$Xresume description]
     * @param   [type]  $Xsauf    [$Xsauf description]
     *
     * @return  [type]            [return description]
     */
    function subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf)
    {
        return SubscribeManager::getInstance()->subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf);
    }
}

if (! function_exists('subscribe_query'))
{
    /**
     * [subscribe_query description]
     *
     * @param   [type]  $Xuser  [$Xuser description]
     * @param   [type]  $Xtype  [$Xtype description]
     * @param   [type]  $Xclef  [$Xclef description]
     *
     * @return  [type]          [return description]
     */
    function subscribe_query($Xuser, $Xtype, $Xclef)
    {
        return SubscribeManager::getInstance()->subscribe_query($Xuser, $Xtype, $Xclef);
    }
}


// Cookie

if (! function_exists('cookiedecode'))
{
    /**
     * [cookiedecode description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  [type]         [return description]
     */
    function cookiedecode($user)
    {
        return CookieManager::getInstance()->cookiedecode($user);
    }
}

// Code

if (! function_exists('change_cod'))
{
    /**
     * [change_cod description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    function change_cod($r)
    {
        return CodeManager::getInstance()->change_cod($r);
    }
}

if (! function_exists('af_cod'))
{
    /**
     * [af_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function af_cod($ibid)
    {
        return CodeManager::getInstance()->af_cod($ibid);
    }
}

if (! function_exists('desaf_cod'))
{
    /**
     * [desaf_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function desaf_cod($ibid)
    {
        return CodeManager::getInstance()->desaf_cod($ibid);
    }
}

if (! function_exists('aff_code'))
{
    /**
     * [aff_code description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    function aff_code($ibid)
    {
        return CodeManager::getInstance()->aff_code($ibid);
    }
}

// Auth

if (! function_exists('secur_static'))
{
    /**
     * [secur_static description]
     *
     * @param   [type]  $sec_type  [$sec_type description]
     *
     * @return  [type]             [return description]
     */
    function secur_static($sec_type)
    {
        return AuthManager::getInstance()->secur_static($sec_type);
    }
}

if (! function_exists('autorisation'))
{
    /**
     * [autorisation description]
     *
     * @param   [type]  $auto  [$auto description]
     *
     * @return  [type]         [return description]
     */
    function autorisation($auto)
    {
        return AuthManager::getInstance()->autorisation($auto);
    }
}

if (! function_exists('guard'))
{
    /**
     * Undocumented function
     *
     * @param [type] $auth
     * @return void
     */
    function guard($auth)
    {
        return AuthManager::getInstance()->is_admin($auth);
    }
}

if (! function_exists('is_admin'))
{
    /**
     * [is_admin description]
     *
     * @param   [type]  $xadmin  [$xadmin description]
     *
     * @return  [type]           [return description]
     */
    function is_admin($xadmin)
    {
        return AuthManager::getInstance()->is_admin($xadmin);
    }
}

if (! function_exists('is_user'))
{
    /**
     * [is_user description]
     *
     * @param   [type]  $xuser  [$xuser description]
     *
     * @return  [type]          [return description]
     */
    function is_user($xuser)
    {
        return AuthManager::getInstance()->is_user($xuser);
    }
}

// Admin

if (! function_exists('formatAidHeader'))
{
    /**
     * [formatAidHeader description]
     *
     * @param   [type]  $aid  [$aid description]
     *
     * @return  [type]        [return description]
     */
    function formatAidHeader($aid)
    {
        return AuthorsManager::getInstance()->formatAidHeader($aid);
    }
}


// Paginator

if (! function_exists('paginate_single'))
{

    function paginate_single($url, $urlmore, $total, $current, $adj, $topics_per_page, $start)
    {
        return PaginatorManager::getInstance()->paginate_single($url, $urlmore, $total, $current, $adj, $topics_per_page, $start);
    }
}

if (! function_exists('paginate'))
{

    function paginate($url, $urlmore, $total, $current, $adj, $topics_per_page, $start)
    {
        return PaginatorManager::getInstance()->paginate($url, $urlmore, $total, $current, $adj, $topics_per_page, $start);
    }
}

// Error

if (! function_exists('forumerror'))
{
    /**
     * [forumerror description]
     *
     * @param   [type]  $e_code  [$e_code description]
     *
     * @return  [type]           [return description]
     */
    function forumerror($e_code)
    {
        Error::code($e_code);
    }
}

// ex : Archive.php

if (! function_exists('get_os'))
{    
    /**
     * [get_os description]
     *
     * @return  [type]  [return description]
     */
    function get_os()
    {
        $client = getenv("HTTP_USER_AGENT");

        if (preg_match('#(\(|; )(Win)#', $client, $regs)) {
            if ($regs[2] == "Win") {
                $MSos = true;
            } else {
                $MSos = false;
            }

        } else {
            $MSos = false;
        }

        return $MSos;
    }
}



// 
if (! function_exists('JavaPopUp'))
{
    function JavaPopUp($F, $T, $W, $H)
    {
        // 01.feb.2002 by GaWax
        if ($T == "") 
            $T = "@ " . time() . " ";

        $PopUp = "'$F','$T','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$H,width=$W,toolbar=no,scrollbars=yes,resizable=yes'";
        
        return $PopUp;
    }
}

if (! function_exists('check_install'))
{
    /**
     * [check_install description]
     *
     * @return  [type]  [return description]
     */
    function check_install()
    {
        // Modification pour IZ-Xinstall - EBH - JPB & PHR
        if (file_exists("storage/IZ-Xinstall.ok")) {
            if (file_exists("install.php") or is_dir("install")) {
                echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <title>App IZ-Xinstall - Installation Configuration</title>
                    </head>
                    <body>
                        <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #000000"><br />
                            App IZ-Xinstall - Installation &amp; Configuration
                        </div>
                        <div style="text-align: center; font-size: 20px; font-family: Arial; font-weight: bold; color: #ff0000"><br />
                            Vous devez supprimer le r&eacute;pertoire "install" ET le fichier "install.php" avant de poursuivre !<br />
                            You must remove the directory "install" as well as the file "install.php" before continuing!
                        </div>
                    </body>
                </html>';
                die();
            }
        } else {
            if (file_exists("install.php") and is_dir("install")) {
                header("location: install.php");
            }
        }  
    }
}

// Denied

// if (! function_exists('access_denied'))
// {
//     /**
//      * [access_denied description]
//      *
//      * @return  [type]  [return description]
//      */
//     function access_denied()
//     {
//         include(module_path("Npds/Controllers/Admin/Die.php"));
//     }
// }

// if (! function_exists('Access_Error'))
// {
//     /**
//      * [Access_Error description]
//      *
//      * @return  [type]  [return description]
//      */
//     function Access_Error()
//     {
//         include(module_path("Npds/Controllers/Admin/Die.php"));
//     }
// }

// if (! function_exists('Admin_alert'))
// {
//     /**
//      * [Admin_alert description]
//      *
//      * @param   [type]  $motif  [$motif description]
//      *
//      * @return  [type]          [return description]
//      */
//     function Admin_alert($motif)
//     {
//         global $admin;

//         Cookie::set("admin", null);

//         unset($admin);

//         Ecr_Log('security', 'auth.inc.php/Admin_alert : ' . $motif, '');

//         $Titlesitename = 'Npds';

//         if (file_exists("storage/meta/meta.php"))
//             include("storage/meta/meta.php");

//         echo '
//             </head>
//             <body>
//                 <br /><br /><br />
//                 <p style="font-size: 24px; font-family: Tahoma, Arial; color: red; text-align:center;"><strong>.: ' . __d('npds', 'Votre adresse Ip est enregistrée') . ' :.</strong></p>
//             </body>
//         </html>';

//         die();
//     }
// }
