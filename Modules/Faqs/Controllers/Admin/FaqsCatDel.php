<?php

namespace Modules\Faqs\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class FaqsCatDel extends AdminController
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
     * [FaqCatDel description]
     *
     * @param   [type]  $id_cat  [$id_cat description]
     * @param   [type]  $ok      [$ok description]
     *
     * @return  [type]           [return description]
     */
    public function FaqCatDel($id_cat, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM faqcategories WHERE id_cat='$id_cat'");
            sql_query("DELETE FROM faqanswer WHERE id_cat='$id_cat'");

            Header("Location: admin.php?op=FaqAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p><strong>' . __d('faqs', 'ATTENTION : êtes-vous sûr de vouloir effacer cette FAQ et toutes ses questions ?') . '</strong></p>
                <a href="admin.php?op=FaqCatDel&amp;id_cat=' . $id_cat . '&amp;ok=1" class="btn btn-danger btn-sm">
                    ' . __d('faqs', 'Oui') . '
                </a>
                &nbsp;
                <a href="admin.php?op=FaqAdmin" class="btn btn-secondary btn-sm">
                    ' . __d('faqs', 'Non') . '
                </a>
            </div>';
        }
    }

}
