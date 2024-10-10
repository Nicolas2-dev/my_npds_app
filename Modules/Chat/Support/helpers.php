<?php

use Modules\Chat\Support\Facades\Chat;

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
        return Chat::if_chat($pour);
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
        return Chat::insertChat($username, $message, $dbname, $id);
    }
}
