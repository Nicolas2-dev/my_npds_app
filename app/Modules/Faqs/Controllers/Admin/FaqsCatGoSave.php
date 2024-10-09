<?php

namespace App\Modules\Faqs\Controllers\Admin;

use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class FaqsCatGoSave extends AdminController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "faqs";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'FaqAdmin';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('faqs', 'Faq');

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
     * [FaqCatGoSave description]
     *
     * @param   [type]  $id        [$id description]
     * @param   [type]  $question  [$question description]
     * @param   [type]  $answer    [$answer description]
     *
     * @return  [type]             [return description]
     */
    public function FaqCatGoSave($id, $question, $answer)
    {
        $question   = stripslashes(Sanitize::FixQuotes($question));
        $answer     = stripslashes(Sanitize::FixQuotes($answer));

        sql_query("UPDATE faqanswer SET question='$question', answer='$answer' WHERE id='$id'");

        Header("Location: admin.php?op=FaqCatGoEdit&id=$id");
    }

}
