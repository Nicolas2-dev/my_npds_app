<?php

use Modules\Stats\Support\Facades\Stat;

/**
 * 
 */
if (! function_exists('req_stat'))
{
    /**
     * [req_stat description]
     *
     * @return  [type]  [return description]
     */
    function req_stat()
    {
        return Stat::req_stat();
    }
    
}
