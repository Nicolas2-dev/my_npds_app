<?php

namespace Modules\Reviews\Controllers\Front;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Language;


class ReviewsMod extends FrontController
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
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function mod_review($id)
    {
        global $admin;
    
        if (($id != 0) && ($admin)) {

            $result = sql_query("SELECT * FROM reviews WHERE id = '$id'");
            $myrow  =  sql_fetch_assoc($result);
    
            $id         =  $myrow['id'];
            $date       = $myrow['date'];
            $title      = $myrow['title'];
            $text       = str_replace('<br />', '\r\n', $myrow['text']);
            $cover      = $myrow['cover'];
            $reviewer   = $myrow['reviewer'];
            $email      = $myrow['email'];
            $hits       = $myrow['hits'];
            $url        = $myrow['url'];
            $url_title  = $myrow['url_title'];
            $score      = $myrow['score'];
    
            echo '
        <h2 class="mb-4">' . __d('reviews', 'Modification d\'une critique') . '</h2>
        <hr />
        <form id="modreview" method="post" action="reviews.php?op=preview_review">
            <input type="hidden" name="id" value="' . $id . '">
            <div class="form-floating mb-3">
                <input type="text" class="form-control w-100" id="date_modrev" name="date" value="' . $date . '" />
                <label for="date_modrev">' . __d('reviews', 'Date') . '</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="title_modrev" name="title" required="required" maxlength="150" style="height:70px;">' . $title . '</textarea>
                <label for="title_modrev">' . __d('reviews', 'Titre') . '</label>
                <span class="help-block text-end" id="countcar_title_modrev"></span>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="text_modrev" name="text" required="required" style="height:70px;">' . $text . '</textarea>
                <label for="text_modrev">' . __d('reviews', 'Texte') . '</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="reviewer_modrev" name="reviewer" value="' . $reviewer . '" required="required" maxlength="25"/>
                <label for="reviewer_modrev">' . __d('reviews', 'Le critique') . '</label>
                <span class="help-block text-end" id="countcar_reviewer_modrev"></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email_modrev" name="email" value="' . $email . '" maxlength="254" required="required"/>
                <label for="email_modrev">' . __d('reviews', 'Email') . '</label>
                <span class="help-block text-end" id="countcar_email_modrev"></span>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="score_modrev" name="score">';
    
            $i = 1;
            $sel = '';
            do {
                if ($i == $score) {
                    $sel = 'selected="selected" ';
                } else {
                    $sel = '';
                }
    
                echo '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
    
                $i++;
            } while ($i <= 10);
    
            echo '
                </select>
                <label for="score_modrev">' . __d('reviews', 'Evaluation') . '</label>
                <span class="help-block">' . __d('reviews', 'Choisir entre 1 et 10 (1=nul 10=excellent)') . '</span>
            </div>
            <div class="form-floating mb-3">
                <input type="url" class="form-control" id="url_modrev" name="url" maxlength="320" value="' . $url . '" />
                <label for="url_modrev">' . __d('reviews', 'Lien') . '</label>
                <span class="help-block">' . __d('reviews', 'Site web officiel. Veillez à ce que votre url commence bien par') . ' http(s)://<span class="float-end" id="countcar_url_modrev"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="url_title_modrev" name="url_title" value="' . $url_title . '"  maxlength="50" />
                <label for="url_title_modrev">' . __d('reviews', 'Titre du lien') . '</label>
                <span class="help-block">' . __d('reviews', 'Obligatoire seulement si vous soumettez un lien relatif') . '<span class="float-end" id="countcar_url_title_modrev"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="cover_modrev" name="cover" value="' . $cover . '" maxlength="100"/>
                <label for="cover_modrev">' . __d('reviews', 'Image de garde') . '</label>
                <span class="help-block">' . __d('reviews', 'Nom de l\'image principale non obligatoire, la mettre dans images/reviews/') . '<span class="float-end" id="countcar_cover_modrev"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="hits_modrev" name="hits" value="' . $hits . '" maxlength="9" />
                <label for="hits_modrev">' . __d('reviews', 'Hits') . '</label>
            </div>
            <input type="hidden" name="op" value="preview_review" />
            <input class="btn btn-primary my-3 me-2" type="submit" value="' . __d('reviews', 'Prévisualiser les modifications') . '" />
            <input class="btn btn-secondary my-3" type="button" onclick="history.go(-1)" value="' . __d('reviews', 'Annuler') . '" />
            </form>
            <script type="text/javascript" src="assets/shared/flatpickr/dist/flatpickr.min.js"></script>
            <script type="text/javascript" src="assets/shared/flatpickr/dist/l10n/' . Language::language_iso(1, '', '') . '.js"></script>
            <script type="text/javascript">
            //<![CDATA[
                $(document).ready(function() {
                    $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "assets/shared/flatpickr/dist/themes/App.css"});
                })
                
            //]]>
            </script>';
    
            $fv_parametres = '
            date:{},
            hits: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,9}$/,
                    message: "0-9"
                    },
                    between: {
                    min: 1,
                    max: 999999999,
                    message: "1 ... 999999999"
                    }
                }
            },
            !###!
            flatpickr("#date_modrev", {
                altInput: true,
                altFormat: "l j F Y",
                dateFormat:"Y-m-d",
                "locale": "' . Language::language_iso(1, '', '') . '",
                onChange: function() {
                    fvitem.revalidateField(\'date\');
                }
            });
            ';
    
            $arg1 = '
            var formulid = ["modreview"];
            inpandfieldlen("title_modrev",150);
            inpandfieldlen("reviewer_modrev",25);
            inpandfieldlen("email_modrev",254);
            inpandfieldlen("url_modrev",320);
            inpandfieldlen("url_title_modrev",50);
            inpandfieldlen("cover_modrev",100);';
    
            sql_free_result($result);
        }
    
        Css::adminfoot('fv', $fv_parametres, $arg1, 'foo');
    }

}
