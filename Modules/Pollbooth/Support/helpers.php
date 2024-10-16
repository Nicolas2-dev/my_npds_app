<?php

use Modules\Pollbooth\Library\PollboothManager;

if (! function_exists('PollNewest'))
{
    /**
     * [PollNewest description]
     *
     * @param   int  $id  [$id description]
     *
     * @return  [type]    [return description]
     */
    function PollNewest(int $id = null)
    {
        return PollboothManager::getInstance()->PollNewest($id);
    }
}

if (! function_exists('pollSecur'))
{
    /**
     * [pollSecur description]
     *
     * @param   [type]  $pollID  [$pollID description]
     *
     * @return  [type]           [return description]
     */
    function pollSecur($pollID)
    {
        return PollboothManager::getInstance()->pollSecur($pollID);
    }
}
