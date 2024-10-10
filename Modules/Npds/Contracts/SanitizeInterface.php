<?php

namespace Modules\Npds\Contracts;


interface SanitizeInterface {

    /**
     * [addslashes_GPC description]
     *
     * @param   [type]  $arr  [$arr description]
     *
     * @return  [type]        [return description]
     */
    public static function addslashes_GPC(&$arr);

    /**
     * [changetoamp description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public static function changetoamp($r);

    /**
     * [changetoampadm description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public static function changetoampadm($r);

    /**
     * [conv2br description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public static function conv2br($txt);

    /**
     * [hexfromchr description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public static function hexfromchr($txt);

    /**
     * [utf8_java description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public static function utf8_java($ibid);

    /**
     * [wrh description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public static function wrh($ibid);

    /**
     * [FixQuotes description]
     *
     * @param   [type]  $what  [$what description]
     *
     * @return  [type]         [return description]
     */
    public static function FixQuotes($what = '');

    /**
     * [split_string_without_space description]
     *
     * @param   [type]  $msg    [$msg description]
     * @param   [type]  $split  [$split description]
     *
     * @return  [type]          [return description]
     */
    public static function split_string_without_space($msg, $split);

    /**
     * [wrapper_f description]
     *
     * @param   [type]  $string  [$string description]
     * @param   [type]  $key     [$key description]
     * @param   [type]  $cols    [$cols description]
     *
     * @return  [type]           [return description]
     */
    public static function wrapper_f(&$string, $key, $cols);
    
    /**
     * [make_clickable description]
     *
     * @param   [type]  $text  [$text description]
     *
     * @return  [type]         [return description]
     */
    public static function make_clickable($text);    

    /**
     * [undo_htmlspecialchars description]
     *
     * @param   [type]  $input  [$input description]
     *
     * @return  [type]          [return description]
     */
    public static function undo_htmlspecialchars($input);

}