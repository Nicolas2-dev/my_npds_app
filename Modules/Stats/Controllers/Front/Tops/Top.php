<?php

namespace Modules\Stats\Controllers\Front\Tops;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


class Top extends FrontController
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
        
                echo Metalang::meta_lang(Language::aff_langue($Xcontent));
            }
        }
        
        // -- SuperCache
        if (($SuperCache) and (!$user)) {
            $cache_obj->endCachingPage();
        }
    }

}
