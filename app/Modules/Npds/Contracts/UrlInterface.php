<?php

namespace App\Modules\Npds\Contracts;


interface UrlInterface {

    /**
     * [redirect description]
     *
     * @param   [type]  $urlx  [$urlx description]
     *
     * @return  [type]         [return description]
     */
    public function redirect($urlx);

    /**
     * Undocumented function
     *
     * @param [type] $urlx
     * @param [type] $time
     * @return void
     */
    public function redirect_time($urlx, $time);

}
