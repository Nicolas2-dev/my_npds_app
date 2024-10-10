<?php

namespace Modules\Faqs\Controllers;

use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;

/**
 * Undocumented class
 */
class FaqShowAll extends FrontController
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
