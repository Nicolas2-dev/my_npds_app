<?php

namespace Modules\Npds\Contracts;


interface PasswordInterface {

    /**
     * [getOptimalBcryptCostParameter description]
     *
     * @param   [type]  $pass       [$pass description]
     * @param   [type]  $AlgoCrypt  [$AlgoCrypt description]
     * @param   [type]  $min_ms     [$min_ms description]
     *
     * @return  [type]              [return description]
     */
    public function getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms = 100);

}
