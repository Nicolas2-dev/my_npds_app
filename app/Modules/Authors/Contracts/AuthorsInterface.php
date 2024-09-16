<?php

namespace App\Modules\Authors\Contracts;


interface AuthorsInterface {

    /**
     * [formatAidHeader description]
     *
     * @param   [type]  $aid  [$aid description]
     *
     * @return  [type]        [return description]
     */
    public function formatAidHeader($aid);

    /**
     * [modulo_droit description]
     *
     * @return  [type]  [return description]
     */
    public function modulo_droit();

    /**
     * [uri_check description]
     *
     * @return  [type]  [return description]
     */
    public function uri_check();

    /**
     * [error_handler description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function error_handler($ibid);

}
