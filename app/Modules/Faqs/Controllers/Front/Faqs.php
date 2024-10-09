<?php

namespace App\Modules\Faqs\Controllers;

use Npds\Config\Config;
use Npds\Supercache\SuperCacheEmpty;
use Npds\Supercache\SuperCacheManager;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Language;

/**
 * Undocumented class
 */
class Faqs extends FrontController
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
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index($myfaq)
    {
        if (!$myfaq) {

            // Include cache manager
            if (Config::get('supercache.SuperCache')) {
                $cache_obj = new SuperCacheManager();
                $cache_obj->startCachingPage();
            } else {
                $cache_obj = new SuperCacheEmpty();
            }
        
            if (($cache_obj->get_Genereting_Output() == 1) 
            or ($cache_obj->get_Genereting_Output() == -1) 
            or (!Config::get('supercache.SuperCache'))) {
                
                $result = sql_query("SELECT id_cat, categories FROM faqcategories ORDER BY id_cat ASC");
        
                echo '
                <h2 class="mb-4">' . __d('faqs', 'FAQ - Questions fréquentes') . '</h2>
                <hr />
                <h3 class="mb-3">' . __d('faqs', 'Catégories') . '<span class="badge bg-secondary float-end">' . sql_num_rows($result) . '</span></h3>
                <div class="list-group">';
        
                while (list($id_cat, $categories) = sql_fetch_row($result)) {
                    
                    $catname = urlencode(Language::aff_langue($categories));

                    echo '<a class="list-group-item list-group-item-action" href="faq.php?id_cat=' . $id_cat . '&amp;myfaq=yes&amp;categories=' . $catname . '">
                        <h4 class="list-group-item-heading">
                            ' . Language::aff_langue($categories) . '
                        </h4>
                    </a>';
                }
        
                echo '</div>';
            }
        
            if (Config::get('supercache.SuperCache')) {
                $cache_obj->endCachingPage();
            }

        } else {
            $title = "FAQ : " . Hack::remove(StripSlashes(Config::get('supercache.SuperCache')));

            // Include cache manager
            if (Config::get('supercache.SuperCache')) {
                $cache_obj = new SuperCacheManager();
                $cache_obj->startCachingPage();
            } else {
                $cache_obj = new SuperCacheEmpty();
            }
        
            if (($cache_obj->get_Genereting_Output() == 1) or ($cache_obj->get_Genereting_Output() == -1) or (!Config::get('supercache.SuperCache'))) {
                $this->ShowFaq($id_cat, Hack::remove($categories));
                $this->ShowFaqAll($id_cat);
            }
        
            if (Config::get('supercache.SuperCache')) {
                $cache_obj->endCachingPage();
            }
        }        
    }

}
