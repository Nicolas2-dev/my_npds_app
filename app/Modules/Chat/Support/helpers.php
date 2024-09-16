<?php

use App\Modules\chat\Library\ChatManager;


if (! function_exists('if_chat($pour)'))
{
    /**
     * [if_chat description]
     *
     * @param   [type]  $pour  [$pour description]
     *
     * @return  [type]         [return description]
     */
    function if_chat($pour)
    {
        return ChatManager::getInstance()->if_chat($pour);
    }
}

if (! function_exists('insertChat'))
{
    /**
     * [insertChat description]
     *
     * @param   [type]  $username  [$username description]
     * @param   [type]  $message   [$message description]
     * @param   [type]  $dbname    [$dbname description]
     * @param   [type]  $id        [$id description]
     *
     * @return  [type]             [return description]
     */
    function insertChat($username, $message, $dbname, $id)
    {
        return ChatManager::getInstance()->insertChat($username, $message, $dbname, $id);
    }
}
