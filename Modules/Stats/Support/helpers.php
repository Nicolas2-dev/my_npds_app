<?php

use Modules\Stats\Library\StatManager;

// Statistique

if (! function_exists('req_stat'))
{
    /**
     * [req_stat description]
     *
     * @return  [type]  [return description]
     */
    function req_stat()
    {
        return StatManager::getInstance()->req_stat();
    }
}
