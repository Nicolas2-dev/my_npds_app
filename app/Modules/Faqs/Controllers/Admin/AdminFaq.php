<?php

namespace App\Modules\Faqs\Controllers\Admin;

use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Code;
use App\Modules\Npds\Support\Facades\Editeur;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;


class AdminFaq extends AdminController
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
     * [faqadmin description]
     *
     * @return  [type]  [return description]
     */
    public function faqadmin()
    {
        $arg1 = '
            var formulid = ["adminfaqcatad"];
            inpandfieldlen("categories",255);
        ';

        $adminfoot = Css::adminfoot('fv', '', $arg1, '');

        $this->title($this->f_titre);

        $this->set('faqs', DB::table('faqcategories')->select('id_cat', 'categories')->orderBy('id_cat', 'asc')->get());

        $this->set('adminfoot', $adminfoot);

    }

    /**
     * [FaqCatGo description]
     *
     * @param   [type]  $id_cat  [$id_cat description]
     *
     * @return  [type]           [return description]
     */
    public function FaqCatGo($id_cat)
    {
        $lst_qr = '';

        $result = sql_query("SELECT fa.id, fa.question, fa.answer, fc.categories FROM faqanswer fa LEFT JOIN faqcategories fc ON fa.id_cat = fc.id_cat WHERE fa.id_cat='$id_cat' ORDER BY id");
        
        while (list($id, $question, $answer, $categories) = sql_fetch_row($result)) {
            
            $faq_cat    = Language::aff_langue($categories);
            $answer     = Code::aff_code(Language::aff_langue($answer));

            $lst_qr .= '
            <li id="qr_' . $id . '" class="list-group-item">
                <div class="topi">
                    <h5 id="q_' . $id . '" class="list-group-item-heading"><a class="" href="admin.php?op=FaqCatGoEdit&amp;id=' . $id . '" title="' . __d('faqs', 'Editer la question réponse') . '" data-bs-toggle="tooltip">' . Language::aff_langue($question) . '</a></h5>
                    <p class="list-group-item-text">' . Metalang::meta_lang($answer) . '</p>
                    <div id="shortcut-tools_' . $id . '" class="n-shortcut-tools" style="display:none;"><a class="text-danger btn" href="admin.php?op=FaqCatGoDel&amp;id=' . $id . '&amp;ok=0" ><i class="fas fa-trash fa-2x" title="' . __d('faqs', 'Supprimer la question réponse') . '" data-bs-toggle="tooltip" data-bs-placement="left"></i></a></div>
                </div>
            </li>';
        }

        echo '
        <hr />
        <h3 class="mb-3">' . $faq_cat . '</h3>
        <h4>' . __d('faqs', 'Ajouter une question réponse') . '</h4>
        <form id="adminfaqquest" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="question">' . __d('faqs', 'Question') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" type="text" name="question" id="question" maxlength="255"></textarea>
                    <span class="help-block text-end"><span id="countcar_question"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="answer">' . __d('faqs', 'Réponse') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" id="answer" name="answer" rows="15"></textarea>
                    </div>
                </div>';

        echo Editeur::aff_editeur("answer", "false");

        echo '
                <div class="mb-3 row">
                    <div class="col-sm-12 d-flex flex-row justify-content-start flex-wrap">
                    <input type="hidden" name="id_cat" value="' . $id_cat . '" />
                    <input type="hidden" name="op" value="FaqCatGoAdd" />' . "\n" . '
                    <button class="btn btn-primary mb-2 " type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('faqs', 'Ajouter') . '</button>&nbsp;
                    <button class="btn btn-secondary mb-2 " href="admin.php?op=FaqAdmin">' . __d('faqs', 'Retour en arrière') . '</button>
                    </div>
                </div>
            </fieldset>
        </form>
        <h4>' . __d('faqs', 'Liste des questions réponses') . '</h4>
        <ul class="list-group">
            ' . $lst_qr . '
        </ul>
        <script type="text/javascript">
            //<![CDATA[
                $(document).ready(function() {
                    var topid="";
                    $(".topi").hover(function(){
                    topid = $(this).parent().attr("id");
                    topid = topid.substr(topid.search(/\d/))
                    $button=$("#shortcut-tools_"+topid);
                    $button.show();
                    }, function(){
                    $button.hide();
                });
                });
            //]]>
        </script>';

        $arg1 = '
            var formulid = ["adminfaqquest"];
            inpandfieldlen("question",255);
        ';

        Css::adminfoot('fv', '', $arg1, '');
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

    /**
     * [FaqCatSave description]
     *
     * @param   [type]  $old_id_cat  [$old_id_cat description]
     * @param   [type]  $id_cat      [$id_cat description]
     * @param   [type]  $categories  [$categories description]
     *
     * @return  [type]               [return description]
     */
    public function FaqCatSave($old_id_cat, $id_cat, $categories)
    {
        $categories = stripslashes(Sanitize::FixQuotes($categories));

        if ($old_id_cat != $id_cat) {
            sql_query("UPDATE faqanswer SET id_cat='$id_cat' WHERE id_cat='$old_id_cat'");
        }

        sql_query("UPDATE faqcategories SET id_cat='$id_cat', categories='$categories' WHERE id_cat='$old_id_cat'");

        Header("Location: admin.php?op=FaqAdmin");
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

    /**
     * [FaqCatAdd description]
     *
     * @param   [type]  $categories  [$categories description]
     *
     * @return  [type]               [return description]
     */
    public function FaqCatAdd($categories)
    {
        $categories = stripslashes(Sanitize::FixQuotes($categories));

        sql_query("INSERT INTO faqcategories VALUES (NULL, '$categories')");

        Header("Location: admin.php?op=FaqAdmin");
    }

    /**
     * [FaqCatGoAdd description]
     *
     * @param   [type]  $id_cat    [$id_cat description]
     * @param   [type]  $question  [$question description]
     * @param   [type]  $answer    [$answer description]
     *
     * @return  [type]             [return description]
     */
    public function FaqCatGoAdd($id_cat, $question, $answer)
    {
        $question   = stripslashes(Sanitize::FixQuotes($question));
        $answer     = stripslashes(Sanitize::FixQuotes($answer));

        sql_query("INSERT INTO faqanswer VALUES (NULL, '$id_cat', '$question', '$answer')");

        Header("Location: admin.php?op=FaqCatGo&id_cat=$id_cat");
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

    /**
     * [FaqCatGoDel description]
     *
     * @param   [type]  $id  [$id description]
     * @param   [type]  $ok  [$ok description]
     *
     * @return  [type]       [return description]
     */
    public function FaqCatGoDel($id, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM faqanswer WHERE id='$id'");

            Header("Location: admin.php?op=FaqAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p><strong>' . __d('faqs', 'ATTENTION : êtes-vous sûr de vouloir effacer cette question ?') . '</strong></p>
                <a href="admin.php?op=FaqCatGoDel&amp;id=' . $id . '&amp;ok=1" class="btn btn-danger btn-sm">
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
