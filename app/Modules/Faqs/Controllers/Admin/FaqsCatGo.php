<?php

namespace App\Modules\Faqs\Controllers\Admin;

use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Code;
use App\Modules\Npds\Support\Facades\Editeur;
use App\Modules\Npds\Support\Facades\Language;
use App\Modules\Npds\Support\Facades\Metalang;

/**
 * Undocumented class
 */
class FaqsCatGo extends AdminController
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

}
