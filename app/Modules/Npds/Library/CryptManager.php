<?php

namespace App\Modules\Npds\Library;


use Npds\Config\Config;
use App\Modules\Npds\Contracts\CryptInterface;


class CryptManager implements CryptInterface 
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
     * [keyED description]
     *
     * @param   [type]  $txt          [$txt description]
     * @param   [type]  $encrypt_key  [$encrypt_key description]
     *
     * @return  [type]                [return description]
     */
    public function keyED($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);

        $ctr = 0;
        $tmp = '';

        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key)) {
                $ctr = 0;
            }

            $tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }

        return $tmp;
    }

    /**
     * [encrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public function encrypt($txt)
    {
        return $this->encryptK($txt, Config::get('npds.Npds_Key'));
    }

    /**
     * [encryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    public function encryptK($txt, $C_key)
    {
        srand((int) microtime() * 1000000);

        $encrypt_key = md5(rand(0, 32000));

        $ctr = 0;
        $tmp = '';

        for ($i = 0; $i < strlen($txt); $i++) {
            if ($ctr == strlen($encrypt_key)) {
                $ctr = 0;
            }

            $tmp .= substr($encrypt_key, $ctr, 1) .(substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1));
            $ctr++;
        }

        return base64_encode($this->keyED($tmp, $C_key));
    }

    /**
     * [decrypt description]
     *
     * @param   [type]  $txt  [$txt description]
     *
     * @return  [type]        [return description]
     */
    public function decrypt($txt)
    {
        return $this->decryptK($txt, Config::get('npds.Npds_Key'));
    }

    /**
     * [decryptK description]
     *
     * @param   [type]  $txt    [$txt description]
     * @param   [type]  $C_key  [$C_key description]
     *
     * @return  [type]          [return description]
     */
    public function decryptK($txt, $C_key)
    {
        $txt = $this->keyED(base64_decode($txt), $C_key);
        $tmp = '';

        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = substr($txt, $i, 1);
            $i++;
            $tmp .= (substr($txt, $i, 1) ^ $md5);
        }

        return $tmp;
    }

}
