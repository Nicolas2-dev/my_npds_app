<?php

namespace App\Modules\Faqs\Controllers;

use Npds\Config\Config;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;
use App\Modules\Npds\Library\Supercache\SuperCacheEmpty;
use App\Modules\Npds\Library\Supercache\SuperCacheManager;


class Front extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

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

    /**
     * [ShowFaq description]
     *
     * @param   [type]  $id_cat      [$id_cat description]
     * @param   [type]  $categories  [$categories description]
     *
     * @return  [type]               [return description]
     */
    public function ShowFaq($id_cat, $categories)
    {
        echo '
        <h2 class="mb-4">' . __d('faqs', 'FAQ - Questions fréquentes') . '</h2>
        <hr />
        <h3 class="mb-3">' . __d('faqs', 'Catégorie') . ' <span class="text-muted"># ' . StripSlashes($categories) . '</span></h3>
        <p class="lead">
            <a href="faq.php" title="' . __d('faqs', 'Retour à l\'index FAQ') . '" data-bs-toggle="tooltip">
                ' . __d('faqs', 'Index') . '
            </a>&nbsp;&raquo;&raquo;&nbsp;' . StripSlashes($categories) . '
        </p>';
    
        $result = sql_query("SELECT id, id_cat, question, answer FROM faqanswer WHERE id_cat='$id_cat'");
        while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
        }
    }
    
    /**
     * [ShowFaqAll description]
     *
     * @param   [type]  $id_cat  [$id_cat description]
     *
     * @return  [type]           [return description]
     */
    public function ShowFaqAll($id_cat)
    {
        $result = sql_query("SELECT id, id_cat, question, answer FROM faqanswer WHERE id_cat='$id_cat'");
    
        while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
            echo '
            <div class="card mb-3" id="accordion_' . $id . '" role="tablist" aria-multiselectable="true">
                <div class="card-body">
                    <h4 class="card-title">
                    <a data-bs-toggle="collapse" data-parent="#accordion_' . $id . '" href="#faq_' . $id . '" aria-expanded="true" aria-controls="' . $id . '">
                        <i class="fa fa-caret-down toggle-icon"></i>
                    </a>&nbsp;' . Language::aff_langue($question) . '
                    </h4>
                    <div class="collapse" id="faq_' . $id . '" >
                        <div class="card-text">
                        ' . Metalang::meta_lang(Language::aff_langue($answer)) . '
                        </div>
                    </div>
                </div>
            </div>';
        }
    }

}
