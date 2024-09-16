<?php

use App\Modules\Messenger\Library\MessengerManager;


if (! function_exists('Mess_Check_Mail'))
{
    /**
     * [Mess_Check_Mail description]
     *
     * @param   [type]  $username  [$username description]
     *
     * @return  [type]             [return description]
     */
    function Mess_Check_Mail($username)
    {
        return MessengerManager::getInstance()->Mess_Check_Mail($username);
    }
}

if (! function_exists('Mess_Check_Mail_interface'))
{
    /**
     * [Mess_Check_Mail_interface description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    function Mess_Check_Mail_interface($username, $class)
    {
        return MessengerManager::getInstance()->Mess_Check_Mail_interface($username, $class);
    }
}

if (! function_exists('Mess_Check_Mail_Sub'))
{
    /**
     * [Mess_Check_Mail_Sub description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $class     [$class description]
     *
     * @return  [type]             [return description]
     */
    function Mess_Check_Mail_Sub($username, $class)
    {
        return MessengerManager::getInstance()->Mess_Check_Mail_Sub($username, $class);
    }
}

if (! function_exists('Form_instant_message'))
{
    /**
     * [Form_instant_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    function Form_instant_message($to_userid)
    {
        return MessengerManager::getInstance()->Form_instant_message($to_userid);
    }
}

if (! function_exists('writeDB_private_message'))
{
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
    function writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie)
    {
        return MessengerManager::getInstance()->writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie);
    }
}

if (! function_exists('write_short_private_message'))
{
    /**
     * [write_short_private_message description]
     *
     * @param   [type]  $to_userid  [$to_userid description]
     *
     * @return  [type]              [return description]
     */
    function write_short_private_message($to_userid)
    {
        return MessengerManager::getInstance()->write_short_private_message($to_userid);
    }
}
