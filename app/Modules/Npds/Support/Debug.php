<?php

namespace App\Modules\Npds\Support;

/**
 * Debug class
 */
class Debug
{
    /**
     * [errorReporting description]
     *
     * @param   [type]  $type  [$type description]
     *
     * @return  [type]         [return description]
     */
    public static function Reporting($type)
    {
        // Modify the report level of PHP
        switch($type) {

            // report NO ERROR
            case 'no_error':
                error_reporting(-1);
                break;

            // Devel report
            case 'dev_report':
                error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE); 
                break;

            // standard ERROR report
            case 'error_report':
                error_reporting(E_ERROR | E_WARNING | E_PARSE); 
                break;
              
            // all error
            case 'all':
                error_reporting(E_ALL);
                break;    
        }

        //
        ini_set('display_errors', 'Off');
    }

}
