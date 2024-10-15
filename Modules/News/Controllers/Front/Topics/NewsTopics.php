<?php

namespace Modules\News\Controllers\Front;

use Npds\Config\Config;
use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class NewsTopic extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        settype($op, 'string');

        if ($op != "maj_subscribe") {
            // include("header.php");
        
            $inclusion = false;
        
            if (file_exists("themes/$theme/views/topics.html"))
                $inclusion = "themes/$theme/views/topics.html";
            elseif (file_exists("themes/default/views/topics.html"))
                $inclusion = "themes/default/views/topics.html";
            else
                echo 'views/topics.html / not find !<br />';
        
            if ($inclusion) {
                ob_start();
                    include($inclusion);
                    $Xcontent = ob_get_contents();
                ob_end_clean();
        
                echo meta_lang(aff_langue($Xcontent));
            }
        
            // include("footer.php");
        } else {
            if (Config::get('npds.subscribe')) {
                if ($user) {
                    $result = sql_query("DELETE FROM subscribe WHERE uid='$cookie[0]' AND topicid!=NULL");
        
                    $selection = sql_query("SELECT topicid FROM topics ORDER BY topicid");
        
                    while (list($topicid) = sql_fetch_row($selection)) {
                        if (isset($Subtopicid)) {
                            if (array_key_exists($topicid, $Subtopicid)) {
                                if ($Subtopicid[$topicid] == "on") {
                                    $resultX = sql_query("INSERT INTO subscribe (topicid, uid) VALUES ('$topicid','$cookie[0]')");
                                }
                            }
                        }
                    }
                    
                    redirect_url("topics.php");
                }
            }
        }
        
    }

}