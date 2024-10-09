<?php

namespace App\Modules\Forum\Controllers;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class Forum extends FrontController
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
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        settype($op, 'string');
        settype($Subforumid, 'array');
        
        if ($op == "maj_subscribe") {
            if ($user) {
                settype($cookie[0], "integer");
        
                $result = sql_query("DELETE FROM subscribe WHERE uid='$cookie[0]' AND forumid!='NULL'");
                $result = sql_query("SELECT forum_id FROM forums ORDER BY forum_index,forum_id");
        
                while (list($forumid) = sql_fetch_row($result)) {
                    if (is_array($Subforumid)) {
                        if (array_key_exists($forumid, $Subforumid)) {
                            $resultX = sql_query("INSERT INTO subscribe (forumid, uid) VALUES ('$forumid','$cookie[0]')");
                        }
                    }
                }
            }
        }
        
        include("header.php");
        
        // -- SuperCache
        if (($SuperCache) and (!$user)) {
            $cache_obj->startCachingPage();
        }
        
        if (($cache_obj->get_Genereting_Output() == 1) 
        or ($cache_obj->get_Genereting_Output() == -1) 
        or (!$SuperCache) or ($user)) {
            $inclusion = false;
        
            settype($catid, 'integer');
        
            if ($catid != '') {
                if (file_exists("themes/$theme/views/forum-cat$catid.html")) {
                    $inclusion = "themes/$theme/views/forum-cat$catid.html";
                } elseif (file_exists("themes/default/views/forum-cat$catid.html")) {
                    $inclusion = "themes/default/views/forum-cat$catid.html";
                }
            }
        
            if ($inclusion == false) {
                if (file_exists("themes/$theme/views/forum-adv.html")) {
                    $inclusion = "themes/$theme/views/forum-adv.html";
                } elseif (file_exists("themes/$theme/views/forum.html")) {
                    $inclusion = "themes/$theme/views/forum.html";
                } elseif (file_exists("themes/default/views/forum.html")) {
                    $inclusion = "themes/default/views/forum.html";
                } else {
                    echo "views/forum.html / not find !<br />";
                }
            }
        
            if ($inclusion) {
                $Xcontent = join('', file($inclusion));
                
                echo meta_lang(aff_langue($Xcontent));
            }
        }
        
        // -- SuperCache
        if (($SuperCache) and (!$user)) {
            $cache_obj->endCachingPage();
        }
        
        include("footer.php");
        
    }

}