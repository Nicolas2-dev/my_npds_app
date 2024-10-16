<?php

namespace Modules\Push\Controllers\Front;

use Npds\Supercache\SuperCacheEmpty;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Push\Support\Facades\Push as LPush;
use Npds\Supercache\SuperCacheManager;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


class PushFaqShow extends FrontController
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
     * case "faq_show": => faq_show($id_cat);
     * 
     * Undocumented function
     *
     * @param [type] $id_cat
     * @return void
     */
    public function faq_show($id_cat)
    {
        if ($SuperCache) {
            $cache_obj = new SuperCacheManager();
            $cache_obj->startCachingPage();
        } else {
            $cache_obj = new SuperCacheEmpty();
        }
        
        if (($cache_obj->genereting_output == 1) or ($cache_obj->genereting_output == -1) or (!$SuperCache)) {    

            LPush::push_header("suite");

            $result = sql_query("SELECT categories FROM faqcategories WHERE id_cat='$id_cat'");
            list($categories) = sql_fetch_row($result);

            $categories = str_replace("'", "\'", $categories);
            echo "document.write('<p align=\"center\"><a name=\"$id\"></a><b>" . Language::aff_langue($categories) . "</b></p>');\n";

            $result = sql_query("SELECT id, id_cat, question, answer FROM faqanswer WHERE id_cat='$id_cat'");

            while (list($id, $id_cat, $question, $answer) = sql_fetch_row($result)) {
                $question = str_replace("'", "\'", $question);

                echo "document.write('<b>" . aff_langue($question) . "</b>');\n";
                echo "document.write('<p align=\"justify\">" . links(LPush::convert_nl(str_replace("'", "\'", Metalang::meta_lang(Code::aff_code(Language::aff_langue($answer)))), "win", "html")) . "</p><br />');\n";
            }

            echo "document.write('.: <a href=\"javascript: history.go(0)\" style=\"font-size: 11px;\">" . __d('push', 'Home') . "</a> :.');\n";

            LPush::push_footer();

            sql_free_result($result);
        }
            
        if ($SuperCache) {
            $cache_obj->endCachingPage();
        }
    }

}
