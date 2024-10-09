<?php

namespace App\Modules\Faqs\Controllers\Admin;

use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class FaqsCatEdite extends AdminController
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
     * [FaqCatEdit description]
     *
     * @param   [type]  $id_cat  [$id_cat description]
     *
     * @return  [type]           [return description]
     */
    public function FaqCatEdit($id_cat)
    {
        $result = sql_query("SELECT categories FROM faqcategories WHERE id_cat='$id_cat'");
        list($categories) = sql_fetch_row($result);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('faqs', 'Editer la catégorie') . '</h3>
        <h4><a href="admin.php?op=FaqCatGo&amp;id_cat=' . $id_cat . '">' . $categories . '</a></h4>
        <form id="adminfaqcated" action="admin.php" method="post">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="categories">' . __d('faqs', 'Nom') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" type="text" name="categories" id="categories" maxlength="255" rows="3" required="required" >' . $categories . '</textarea>
                    <span class="help-block text-end"><span id="countcar_categories"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                    <input type="hidden" name="op" value="FaqCatSave" />
                    <input type="hidden" name="old_id_cat" value="' . $id_cat . '" />
                    <input type="hidden" name="id_cat" value="' . $id_cat . '" />
                    <button class="btn btn-outline-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;' . __d('faqs', 'Sauver les modifications') . '</button>
                    </div>
                </div>
            </fieldset>
        </form>';

        $arg1 = '
            var formulid = ["adminfaqcated"];
            inpandfieldlen("categories",255);
        ';

        Css::adminfoot('fv', '', $arg1, '');
    }

}
