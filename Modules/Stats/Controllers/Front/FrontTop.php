<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontTop extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        include("header.php");
        
        if (($SuperCache) and (!$user)) {
            $cache_obj->startCachingPage();
        }
        
        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache) or ($user)) {
            $inclusion = false;
        
            if (file_exists("themes/$theme/views/top.html")) {
                $inclusion = "themes/$theme/views/top.html";
            } elseif (file_exists("themes/default/views/top.html")) {
                $inclusion = "themes/default/views/top.html";
            } else { 
                echo "html/top.html / not find !<br />";
            }
        
            if ($inclusion) {
                ob_start();
                    include ($inclusion);
                    $Xcontent = ob_get_contents();
                ob_end_clean();
        
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