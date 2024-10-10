<?php

namespace Modules\Chat\Library;

use Npds\Http\Request;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Hack;
use Modules\Blocks\Support\Facades\Block;
use Modules\Chat\Contracts\ChatInterface;

/**
 * Undocumented class
 */
class ChatManager implements ChatInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
    * [if_chat description]
    *
    * @param   [type]  $pour  [$pour description]
    *
    * @return  [type]         [return description]
    */
    public function if_chat($pour)
    {
        $auto = Block::autorisation_block("params#" . $pour);

        $dimauto        = count($auto);
        $numofchatters  = 0;
    
        if ($dimauto <= 1) {
            $result = sql_query("SELECT DISTINCT ip FROM chatbox WHERE id='" . $auto[0] . "' AND date >= " . (time() - (60 * 3)) . "");
            $numofchatters = sql_num_rows($result);
        }
    
        return $numofchatters;
    }
    
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
    public function insertChat($username, $message, $dbname, $id)
    {
        if ($message != '') {
            $username = Hack::remove(stripslashes(Sanitize::FixQuotes(strip_tags(trim($username)))));
            $message =  Hack::remove(stripslashes(Sanitize::FixQuotes(strip_tags(trim($message)))));
    
            $ip = Request::getip();

            sql_query("INSERT INTO chatbox VALUES ('" . $username . "', '" . $ip . "', '" . $message . "', '" . time() . "', '$id', " . $dbname . ")");
        }
    }

}
