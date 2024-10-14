<?php

namespace Modules\Npds\Contracts;


interface SecureInterface {

    /**
     * [post description]
     *
     * @param   [type]  $arr  [$arr description]
     * @param   [type]  $key  [$key description]
     *
     * @return  [type]        [return description]
     */
    public static function post($arr,$key);

    /**
     * [url description]
     *
     * @param   [type]  $arr  [$arr description]
     * @param   [type]  $key  [$key description]
     *
     * @return  [type]        [return description]
     */
    public static function url($arr, $key);    

}