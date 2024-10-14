<?php

namespace Modules\Npds\Library;

use Npds\Routing\Url;
use Npds\Support\Facades\DB;
use Modules\Npds\Contracts\PasswordInterface;

/**
 * Undocumented class
 */
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

    /**
     * Undocumented function
     *
     * @param [type] $pass
     * @return string
     */
    public function hash($pass)
    {
        $AlgoCrypt  = PASSWORD_BCRYPT;
        $min_ms     = 100;
        $options    = ['cost' => $this->getOptimalBcryptCostParameter($pass, $AlgoCrypt, $min_ms),];
        $hashpass   = password_hash($pass, PASSWORD_BCRYPT, $options);
        
        return $hashpass;
    }

    /**
     * Undocumented function
     *
     * @param [type] $input
     * @return void
     */
    public function crypt($pass)
    {
        return crypt($pass, $this->hash($pass));
    }

    /**
     * Undocumented function
     *
     * @param [type] $pass
     * @param [type] $setinfo
     * @param [type] $uname
     * @return void
     */
    public function npds_update_news_crypt_password($pass, $setinfo, $uname)
    {
        if (password_verify($pass, $setinfo['pass'])) {

            $hashpass   = $this->hash($pass);
            $cryptPass  = crypt($pass, $hashpass); 

            DB::table('users')->where('uname', $uname)->update([
                'pass'      => $cryptPass,
                'hashkey'   => 1
            ]);

            $newinfo = DB::table('users')
                ->select('pass', 'hashkey', 'uid', 'uname', 'storynum', 'umode', 'uorder', 'thold', 'noscore', 'ublockon', 'theme', 'commentmax', 'user_langue')
                ->where('uname', $uname)
                ->first();

            $newCryptPass = crypt($newinfo['pass'], $hashpass);
            
            if (password_verify($newinfo['pass'], $newCryptPass)) {
                $CryptpPWD = $cryptPass;
            } else {
                return Url::redirect('user/login?stop=1');
            }

            return $CryptpPWD;
        } else {
            return Url::redirect('user/login?stop=1');
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $setinfo
     * @param [type] $pass
     * @return void
     */
    public function npds_verify_password($setinfo, $pass)
    {
        if (password_verify(urldecode($pass), $setinfo['pass']) or password_verify($pass, $setinfo['pass'])) {
            $CryptpPWD = $setinfo['pass'];
        } else {
            return Url::redirect('user/login?stop=1');
        }

        return $CryptpPWD;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    function makePass()
    {
        $syllables      = 'Er@1,In@2,Ti#a3,D#un4,F_e5,P_re6,V!et7,J!o8,Ne%s9,A%l0,L*en1,So*n2,Ch$a3,I$r4,L^er5,Bo^6,Ok@7,!Tio8,N@ar9,0Sim,1P$le,2B*la,3Te!n,4T~oe,5Ch~o,6Co,7Lat,8Spe,9Ak,0Er,1Po,2Co,3Lor,4Pen,5Cil!,6Li!,7Ght,8_Wh,9_At,T#he0,#He1,@Ck2,Is@3,M1am@,B2o+,3No@,Fi-4,0Ve!,A9ny#,Wa7y$,P8ol%,Iti^6,Cs~5,Ra*,@Dio,+Sou,-Rce,!Sea,#Rch,$Pa,&Per,^Com,~Bo,*Sp,Eak1*,S2t~,Fi^,R3st&,Gr#,O5up@,!Boy,Ea!,Gle*,4Tr*,+A1il,B0i+,_Bl9e,Br8b_,P7ri#,De6e!,$Ka3y,1En$,2Be-,4Se-';
        $syllable_array = explode(',', $syllables);
        
        srand((float) microtime() * 1000000);
        
        for ($count = 1; $count <= 4; $count++) {
            if (rand() % 10 == 1) {
                $makepass = sprintf("%0.0f", (rand() % 50) + 1);
            } else {
                $makepass = sprintf("%s", $syllable_array[rand() % 62]);
            }
        }
    
        return $makepass;
    }

}
