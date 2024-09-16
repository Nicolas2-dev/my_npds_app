<?php

namespace App\Modules\Npds\Library;

use App\Modules\Npds\Contracts\PasswordInterface;



class PasswordManager implements PasswordInterface 
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
     * [getOptimalBcryptCostParameter description]
     *
     * @param   [type]  $pass       [$pass description]
     * @param   [type]  $AlgoCrypt  [$AlgoCrypt description]
     * @param   [type]  $min_ms     [$min_ms description]
     *
     * @return  [type]              [return description]
     */
    public function getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms = 100)
    {
        for ($i = 8; $i < 13; $i++) {
            $calculCost = ['cost' => $i];
            $time_start = microtime(true);

            password_hash($pass, $AlgoCrypt, $calculCost);

            $time_end = microtime(true);

            if (($time_end - $time_start) * 1000 > $min_ms) {
                return $i;
            }
        }
    }

}
