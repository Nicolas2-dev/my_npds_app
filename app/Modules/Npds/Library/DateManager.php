<?php

namespace App\Modules\Npds\Library;

use Npds\Config\Config;
use function PHP81_BC\strftime;
use App\Modules\Npds\Contracts\DateInterface;


class DateManager implements DateInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [NightDay description]
     *
     * @return  [type]  [return description]
     */
    public function NightDay()
    {
        $Maintenant = strtotime("now");

        $Jour = strtotime(Config::get('npds.lever'));
        $Nuit = strtotime(Config::get('npds.coucher'));

        if ($Maintenant - $Jour < 0 xor $Maintenant - $Nuit > 0) {
            return "Nuit";
        } else {
            return "Jour";
        }
    }

    /**
     * [formatTimestamp description]
     *
     * @param   [type]  $time  [$time description]
     *
     * @return  [type]         [return description]
     */
    public function formatTimestamp($time)
    {
        global $datetime;

        $local_gmt = Config::get('npds.gmt');

        setlocale(LC_TIME, aff_langue(Config::get('npds.locale')));

        if (substr($time, 0, 5) == 'nogmt') {
            $time = substr($time, 5);
            $local_gmt = 0;
        }

        preg_match('#^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$#', $time, $datetime);
        $datetime = strftime("%A %d %B %Y @ %H:%M:%S", mktime($datetime[4] + (int)$local_gmt, $datetime[5], $datetime[6], $datetime[2], $datetime[3], $datetime[1]), Config::get('npds.locale'));
        
        return ucfirst(htmlentities($datetime, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, cur_charset));
    }

    /**
     * [convertdateTOtimestamp description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    public function convertdateTOtimestamp($myrow)
    {
        if (substr($myrow, 2, 1) == "-") {
            $day = substr($myrow, 0, 2);
            $month = substr($myrow, 3, 2);
            $year = substr($myrow, 6, 4);
        } else {
            $day = substr($myrow, 8, 2);
            $month = substr($myrow, 5, 2);
            $year = substr($myrow, 0, 4);
        }
    
        $hour = substr($myrow, 11, 2);
        $mns = substr($myrow, 14, 2);
        $sec = substr($myrow, 17, 2);
        $tmst = mktime((int) $hour, (int) $mns, (int) $sec, (int) $month, (int) $day, (int) $year);
    
        return $tmst;
    }

    /**
     * [post_convertdate description]
     *
     * @param   [type]  $tmst  [$tmst description]
     *
     * @return  [type]         [return description]
     */
    public function post_convertdate($tmst)
    {
        $val = $tmst > 0 ? date("d-m-Y H:i", $tmst) : '';
    
        return $val;
    }
    
    /**
     * [convertdate description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    public function convertdate($myrow)
    {
        $tmst = static::convertdateTOtimestamp($myrow);
        $val = static::post_convertdate($tmst);
    
        return $val;
    }

}