<?php

namespace App\Modules\Npds\Contracts;


interface MailerInterface {

    /**
     * [send_email description]
     *
     * @param   [type] $email     [$email description]
     * @param   [type] $subject   [$subject description]
     * @param   [type] $message   [$message description]
     * @param   [type] $from      [$from description]
     * @param   [type] $priority  [$priority description]
     * @param   false  $mime      [$mime description]
     * @param   text   $file      [$file description]
     *
     * @return  [type]            [return description]
     */
    public function send_email($email, $subject, $message, $from = "", $priority = false, $mime = "text", $file = null);

    /**
     * [copy_to_email description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     * @param   [type]  $sujet      [$sujet description]
     * @param   [type]  $message    [$message description]
     *
     * @return  [type]              [return description]
     */
    public function copy_to_email($to_userid, $sujet, $message);

    /**
     * [fakedmail description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public function fakedmail($r);

    /**
     * [checkdnsmail description]
     *
     * @param   [type]  $email  [$email description]
     *
     * @return  [type]          [return description]
     */
    public function checkdnsmail($email);
    
    /**
     * [isbadmailuser description]
     *
     * @param   [type]  $utilisateur  [$utilisateur description]
     *
     * @return  [type]                [return description]
     */
    public function isbadmailuser($utilisateur);    

}
