<?php

namespace App\Modules\Faqs\Controllers;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class FaqShow extends FrontController
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

}
