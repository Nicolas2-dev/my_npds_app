<?php

namespace Modules\Faqs\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Editeur;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;

/**
 * Undocumented class
 */
class FaqsCatGoEdite extends AdminController
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
     * [FaqCatGoEdit description]
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public function FaqCatGoEdit($id)
    {
        global $local_user_language;

        $result = sql_query("SELECT fa.question, fa.answer, fa.id_cat, fc.categories FROM faqanswer fa LEFT JOIN faqcategories fc ON fa.id_cat = fc.id_cat WHERE fa.id='$id'");
        list($question, $answer, $id_cat, $faq_cat) = sql_fetch_row($result);

        echo '
        <hr />
        <h3 class="mb-3">' . $faq_cat . '</h3>
        <h4>' . $question . '</h4>
        <h4>' . __d('faqs', 'Prévisualiser') . '</h4>';

        echo '
        <label class="col-form-label" for="">'
                . Language::aff_local_langue('', 'local_user_language', __d('faqs', 'Langue de Prévisualisation')) . '
        </label>
        <div class="card card-body mb-3">
        <p>' . Language::preview_local_langue($local_user_language, $question) . '</p>';

        $answer = Code::aff_code($answer);

        echo '<p>' . Metalang::meta_lang(Language::preview_local_langue($local_user_language, $answer)) . '</p>
        </div>';

        echo '
        <h4>' . __d('faqs', 'Editer Question & Réponse') . '</h4>
        <form id="adminfaqquested" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-12" for="question">' . __d('faqs', 'Question') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" type="text" name="question" id="question" maxlength="255">' . $question . '</textarea>
                    <span class="help-block text-end"><span id="countcar_question"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-12" for="answer">' . __d('faqs', 'Réponse') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" name="answer" rows="15">' . $answer . '</textarea>
                    </div>
                </div>
                ' . Editeur::aff_editeur('answer', '') . '
                <div class="mb-3 row">
                    <div class="col-sm-12 d-flex flex-row justify-content-center flex-wrap">
                    <input type="hidden" name="id" value="' . $id . '" />
                    <input type="hidden" name="op" value="FaqCatGoSave" />
                    <button class="btn btn-outline-primary col-sm-6 mb-2 " type="submit">' . __d('faqs', 'Sauver les modifications') . '</button>
                    <button class="btn btn-outline-secondary col-sm-6 mb-2 " href="admin.php?op=FaqCatGo&amp;id_cat=' . $id_cat . '" >' . __d('faqs', 'Retour en arrière') . '</a>
                    </div>
                </div>
            </fieldset>
        </form>';

        $arg1 = '
            var formulid = ["adminfaqquested"];
            inpandfieldlen("question",255);
        ';

        Css::adminfoot('fv', '', $arg1, '');
    }

}
