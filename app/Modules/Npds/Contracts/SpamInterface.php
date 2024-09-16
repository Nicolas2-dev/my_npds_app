<?php

namespace App\Modules\Npds\Contracts;


interface SpamInterface {

    /**
     * [preg_anti_spam description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function preg_anti_spam($ibid);

    /**
     * [anti_spam description]
     *
     * @param   [type]  $str       [$str description]
     * @param   [type]  $highcode  [$highcode description]
     *
     * @return  [type]             [return description]
     */
    public function anti_spam($str, $highcode = 0);
    
    /**
     * [Q_spambot description]
     *
     * @return  [type]  [return description]
     */
    public function Q_spambot();
    
    /**
     * [L_spambot description]
     *
     * @param   [type]  $ip      [$ip description]
     * @param   [type]  $status  [$status description]
     *
     * @return  [type]           [return description]
     */
    public function L_spambot($ip, $status);
    
    /**
     * [R_spambot description]
     *
     * @param   [type]  $asb_question  [$asb_question description]
     * @param   [type]  $asb_reponse   [$asb_reponse description]
     * @param   [type]  $message       [$message description]
     *
     * @return  [type]                 [return description]
     */
    public function R_spambot($asb_question, $asb_reponse, $message = '');
    
    /**
     * [spam_boot description]
     *
     * @return  [type]  [return description]
     */
    public function spam_boot();    

}
