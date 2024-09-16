<?php

namespace App\Modules\Messenger\Contracts;


interface MessengerInterface {

    /**
     * [Mess_Check_Mail description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail($username);

    /**
     * [Mess_Check_Mail_interface description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail_interface($username, $class);
    
    /**
     * [Mess_Check_Mail_Sub description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    public function Mess_Check_Mail_Sub($username, $class);

    /**
     * [Form_instant_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    public function Form_instant_message($to_userid);

    /**
     * [writeDB_private_message description]
     *
     * @param   [type]  $to_userid    [$to_userid description]
     * @param   [type]  $image        [$image description]
     * @param   [type]  $subject      [$subject description]
     * @param   [type]  $from_userid  [$from_userid description]
     * @param   [type]  $message      [$message description]
     * @param   [type]  $copie        [$copie description]
     *
     * @return  [type]                [return description]
     */
    public function writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie);
    
    /**
     * [write_short_private_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    public function write_short_private_message($to_userid);   

}
