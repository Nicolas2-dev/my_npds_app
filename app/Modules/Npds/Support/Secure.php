<?php

namespace App\Modules\Npds\Support;

use Npds\Routing\Url;
use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Spam;
use App\Modules\npds\Contracts\SecureInterface;

/**
 * Undocumented class
 */
class Secure implements SecureInterface
{

    /**
     * [$bad_uri_name description]
     *
     * @var [type]
     */
    protected static $bad_uri_name = array('GLOBALS', '_SERVER', '_REQUEST', '_GET', '_POST', '_FILES', '_ENV', '_COOKIE', '_SESSION');
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function ghost_form()
    {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            Ecr_Log('security', 'Ghost form in user registration. => NO REFERER', '');
            Spam::L_spambot('', "false");
    
            Url::redirect('denied');
    
        } else if ($_SERVER['HTTP_REFERER'] . Config::get('npds.Npds_Key') !== site_url('user/newuser') . Config::get('npds.Npds_Key')) {
            Ecr_Log('security', 'Ghost form in user registration. => ' . $_SERVER["HTTP_REFERER"], '');
            Spam::L_spambot('', "false");

            Url::redirect('denied');
        }        
    }

    /**
     * [post description]
     *
     * @param   [type]  $arr  [$arr description]
     * @param   [type]  $key  [$key description]
     *
     * @return  [type]        [return description]
     */
    public static function post($arr, $key) 
    {
        $bad_uri_content = Config::get('url_protect.bad_uri_content');

        // mieux faire face aux techniques d'évasion de code : base64_decode(utf8_decode(bin2hex($arr))));
        $arr = rawurldecode($arr);
        $RQ_tmp = strtolower($arr);
        $RQ_tmp_large = strtolower($key) . "=" . $RQ_tmp;

        if (
            in_array($RQ_tmp, $bad_uri_content)
            or
            in_array($RQ_tmp_large, $bad_uri_content)
            or
            in_array($key, static::bad_uri_key(), true)
            or
            count(static::badname_in_uri()) > 0
        ) {
            return Url::redirect('denied');
        }
    }

    /**
     * [url description]
     *
     * @param   [type]  $arr  [$arr description]
     * @param   [type]  $key  [$key description]
     *
     * @return  [type]        [return description]
     */
    public static function url($arr, $key)
    {
        $bad_uri_content = Config::get('url_protect.bad_uri_content');

        // mieux faire face aux techniques d'évasion de code : base64_decode(utf8_decode(bin2hex($arr))));
        $arr = rawurldecode($arr);
        $RQ_tmp = strtolower($arr);
        $RQ_tmp_large = strtolower($key) . "=" . $RQ_tmp;

        if (
            in_array($RQ_tmp, $bad_uri_content)
            or
            in_array($RQ_tmp_large, $bad_uri_content)
            or
            in_array($key, static::bad_uri_key(), true)
            or
            count(static::badname_in_uri()) > 0
        ) {
            return Url::redirect('denied');
        }
    }

    /**
     * [badname_in_uri description]
     *
     * @return  [type]  [return description]
     */
    private static function badname_in_uri()
    {
        return array_intersect(array_keys($_GET), static::$bad_uri_name);        
    }

    /**
     * [bad_uri_key description]
     *
     * @return  [type]  [return description]
     */
    private static function bad_uri_key()
    {
        return array_keys($_SERVER);
    }

}
