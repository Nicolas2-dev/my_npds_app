<?php

namespace App\Modules\Npds\Support;

use Npds\Config\Config;

/**
 * Referer class
 */
class Referer
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function update()
    {
        global $admin;

        if (Config::get('npds.httpref') == 1) {
            $referer = htmlentities(strip_tags(removeHack(getenv("HTTP_REFERER"))), ENT_QUOTES, cur_charset);
            
            if ($referer != '' and !strstr($referer, "unknown") and !stristr($referer, $_SERVER['SERVER_NAME'])) {
                sql_query("INSERT INTO referer VALUES (NULL, '$referer')");
            }
        }
        
    }

}
