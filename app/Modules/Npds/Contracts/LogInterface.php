<?php

namespace App\Modules\Npds\Contracts;


interface LogInterface {

    /**
     * [Ecr_Log description]
     *
     * @param   [type]  $fic_log  [$fic_log description]
     * @param   [type]  $req_log  [$req_log description]
     * @param   [type]  $mot_log  [$mot_log description]
     *
     * @return  [type]            [return description]
     */
    function Ecr_Log($fic_log, $req_log, $mot_log);

}
