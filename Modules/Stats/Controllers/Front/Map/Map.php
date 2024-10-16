<?php

namespace Modules\Stats\Controllers\Front\Map;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Stats\Support\Facades\Map as LMap;
use Npds\Supercache\SuperCacheManager;


class Map extends FrontController
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
        // Include cache manager classe
        global $SuperCache;
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $CACHE_TIMINGS['map.php'] = 3600;
            $CACHE_QUERYS['map.php'] = '^';
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!$SuperCache)) {
            echo '
            <h2>' . __d('stats', 'Plan du site') . '</h2>
            <hr />';
        
            LMap::mapsections();
            LMap::mapforum();
            LMap::maptopics();
            LMap::mapcategories();
            LMap::mapfaq();
        
            echo '<br />';
        
            if (file_exists("themes/default/include/user.inc")) {
                include("themes/default/include/user.inc");
            }
        }
        
        if ($SuperCache){
            $cache_obj->endCachingPage();
        } 
    }

}
