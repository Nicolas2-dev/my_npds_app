<?php

namespace App\Modules\Npds\Contracts;


interface DateInterface {

    /**
     * [NightDay description]
     *
     * @return  [type]  [return description]
     */
    public function NightDay();

    /**
     * [formatTimestamp description]
     *
     * @param   [type]  $time  [$time description]
     *
     * @return  [type]         [return description]
     */
    public function formatTimestamp($time);

    /**
     * [convertdateTOtimestamp description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    public function convertdateTOtimestamp($myrow);

    /**
     * [post_convertdate description]
     *
     * @param   [type]  $tmst  [$tmst description]
     *
     * @return  [type]         [return description]
     */
    public function post_convertdate($tmst);    

    /**
     * [convertdate description]
     *
     * @param   [type]  $myrow  [$myrow description]
     *
     * @return  [type]          [return description]
     */
    public function convertdate($myrow);

}
