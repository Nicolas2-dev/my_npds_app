<?php

namespace Modules\Push\Controllers\Front;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Push\Support\Facades\Push as LPush;
use Npds\Supercache\SuperCacheManager;


class Push extends FrontController
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
        global $options;

        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {
            LPush::push_header("menu");
            LPush::push_menu();

            if (substr($options, 0, 1) == 1) {
                LPush::push_news();
            }

            if (substr($options, 1, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                LPush::push_faq();
            }

            if (substr($options, 2, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                LPush::push_poll();
            }

            if (substr($options, 3, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                LPush::push_members();
            }

            if (substr($options, 4, 1) == 1) {
                echo "document.write('<hr width=\"100%\" noshade=\"noshade\" />');\n";
                LPush::push_links();
            }

            LPush::push_menu();
            echo "document.write('</td></tr><tr><td align=\"center\">');\n";
            echo "document.write('<a href=\"http://www.App.org\">App Push System</a>');\n";
            LPush::push_footer();
        }
            
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
