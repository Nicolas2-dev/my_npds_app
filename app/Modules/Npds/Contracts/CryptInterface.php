<?php

namespace App\Modules\Npds\Contracts;


interface CryptInterface {

    /**
     * [keyED description]
     *
     * @param   [type]  $txt          [$txt description]
     * @param   [type]  $encrypt_key  [$encrypt_key description]
     *
     * @return  [type]                [return description]
     */
    public function keyED($txt, $encrypt_key);

    /**
     * [encrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public function encrypt($txt);

    /**
     * [encryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    public function encryptK($txt, $C_key);

    /**
     * [decrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public function decrypt($txt);

    /**
     * [decryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    public function decryptK($txt, $C_key);
    
}