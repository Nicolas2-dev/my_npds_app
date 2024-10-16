<?php

namespace Modules\Reviews\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class Reviews extends AdminController
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
    protected $hlpfile = 'reviews';

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
    protected $f_meta_nom = 'reviews';


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
        $this->f_titre = __d('reviews', 'Critiques');

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
     * @param [type] $title
     * @param [type] $description
     * @return void
     */
    public function mod_main($title, $description)
    {
        $title          = stripslashes(FixQuotes($title));
        $description    = stripslashes(FixQuotes($description));
    
        sql_query("UPDATE reviews_main SET title='$title', description='$description'");
    
        Header("Location: admin.php?op=reviews");
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function reviews()
    {
        $resultrm = sql_query("SELECT title, description FROM reviews_main");
        list($title, $description) = sql_fetch_row($resultrm);
    
        echo '
        <hr />
        <h3>' . __d('reviews', 'Configuration de la page') . '</h3>
        <form id="reviewspagecfg" class="" action="admin.php" method="post">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="tit_cri">' . __d('reviews', 'Titre de la Page des Critiques') . '</label>
                    <div class="col-sm-12">
                    <input class="form-control" type="text" id="tit_cri" name="title" value="' . $title . '" maxlength="100" />
                    <span class="help-block text-end" id="countcar_tit_cri"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="description">' . __d('reviews', 'Description de la Page des Critiques') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" id="description" name="description" rows="10">' . $description . '</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                    <input type="hidden" name="op" value="mod_main" />
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check-square fa-lg"></i>&nbsp;' . __d('reviews', 'Sauver les modifications') . '</button>
                    </div>
                </div>
            </fieldset>
        </form>
        <hr />';
    
        $result     = sql_query("SELECT * FROM reviews_add ORDER BY id");
        $numrows    = sql_num_rows($result);
    
        echo '<h3>' . __d('reviews', 'Critiques en attente de validation') . '<span class="badge bg-danger float-end">' . $numrows . '</span></h3>';
    
        $jsfvc = '';
        $jsfvf = '';
    
        if ($numrows > 0) {
            while (list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title) = sql_fetch_row($result)) {
                $title  = stripslashes($title);
                $text   = stripslashes($text);
    
                echo '
                <h4 class="my-3">' . __d('reviews', 'Ajouter la critique N° : ') . ' ' . $id . '</h4>
                <form id="reviewsaddcr' . $id . '" action="admin.php" method="post">
                <input type="hidden" name="id" value="' . $id . '" />
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4" for="reviewdate">' . __d('reviews', 'Date') . '</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-check fa-lg"></i></span>
                            <input class="form-control reviewdate-js" type="text" id="reviewdate" name="date" value="' . $date . '" maxlength="10" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4" for="title' . $id . '">' . __d('reviews', 'Nom du produit') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="title' . $id . '" name="title" value="' . $title . '" maxlength="40" required="required" />
                            <span class="help-block text-end" id="countcar_title' . $id . '"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="text' . $id . '">' . __d('reviews', 'Texte') . '</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="text' . $id . ' name="text" rows="6">' . $text . '</textarea>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="reviewer' . $id . '">' . __d('reviews', 'Le critique') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="reviewer' . $id . '" name="reviewer" value="' . $reviewer . '" maxlength="20" required="required" />
                            <span class="help-block text-end" id="countcar_reviewer' . $id . '"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="email' . $id . '">' . __d('reviews', 'E-mail') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="email" id="email' . $id . '" name="email" value="' . $email . '" maxlength="60" required="required" />
                            <span class="help-block text-end" id="countcar_email' . $id . '"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="score' . $id . '">' . __d('reviews', 'Note') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="number" id="score' . $id . '" name="score" value="' . $score . '"  min="1" max="10" />
                        </div>
                    </div>';
    
                if ($url != '') {
                    echo '
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="url' . $id . '">' . __d('reviews', 'Liens relatifs') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="url" id="url' . $id . '" name="url" value="' . $url . '" maxlength="100" />
                            <span class="help-block text-end" id="countcar_url' . $id . '"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4 " for="url_title' . $id . '">' . __d('reviews', 'Titre du lien') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="url_title' . $id . '" name="url_title" value="' . $url_title . '" maxlength="50" />
                            <span class="help-block text-end" id="countcar_url_title' . $id . '"></span>
                        </div>
                    </div>';
                }
    
                echo '
                    <div class="mb-3 row">
                        <label class="col-form-label col-sm-4" for="cover' . $id . '">' . __d('reviews', 'Image de garde') . '</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" id="cover' . $id . '" name="cover" maxlength="100" />
                            <span class="help-block">150*150 pixel => images/covers<span class="float-end ms-1" id="countcar_cover' . $id . '"></span></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-8 ms-sm-auto">
                            <input type="hidden" name="op" value="add_review">
                            <button class="btn btn-primary" type="submit">' . __d('reviews', 'Ajouter') . '</button>
                            <a href="admin.php?op=deleteNotice&amp;id=' . $id . '&amp;op_back=reviews" class="btn btn-danger" role="button">' . __d('reviews', 'Supprimer') . '</a>
                        </div>
                    </div>
                </form>';
    
                $jsfvf .= ',"reviewsaddcr' . $id . '"';
    
                $jsfvc .= '
                inpandfieldlen("title' . $id . '",40);
                inpandfieldlen("reviewer' . $id . '",20);
                inpandfieldlen("email' . $id . '",60);
                inpandfieldlen("url' . $id . '",100);
                inpandfieldlen("url_title' . $id . '",50);
                inpandfieldlen("cover' . $id . '",100);';
            }
    
            $arg1 = '
                var formulid = ["reviewspagecfg"' . $jsfvf . '];
                inpandfieldlen("tit_cri",100);' . $jsfvc;
    
            echo '
            <script type="text/javascript" src="assets/shared/flatpickr/dist/flatpickr.min.js"></script>
            <script type="text/javascript" src="assets/shared/flatpickr/dist/l10n/' . language_iso(1, '', '') . '.js"></script>
            <script type="text/javascript">
            //<![CDATA[
                $(document).ready(function() {
                    $("<link>").appendTo("head").attr({type: "text/css", rel: "stylesheet",href: "assets/shared/flatpickr/dist/themes/App.css"});
                })
                flatpickr(".reviewdate-js", {
                    altInput: true,
                    altFormat: "l j F Y",
                    dateFormat:"Y-m-d",
                    "locale": "' . language_iso(1, '', '') . '",
                });
            //]]>
            </script>';
        } else {
            echo '
            <div class="alert alert-success my-3">' . __d('reviews', 'Aucune critique à ajouter') . '</div>';
    
            $arg1 = '
            var formulid = ["reviewspagecfg"];
            inpandfieldlen("tit_cri",100);';
        }
    
        echo '
        <hr />
        <p><a href="reviews.php?op=write_review" >' . __d('reviews', 'Cliquer ici pour proposer une Critique.') . '</a></p>
        <hr />
        <h3 class="my-3">' . __d('reviews', 'Effacer / Modifier une Critique') . '</h3>
        <div class="alert alert-success">'
                . __d('reviews', 'Vous pouvez simplement Effacer / Modifier les Critiques en naviguant sur') . ' <a href="reviews.php" >reviews.php</a> ' . __d('reviews', 'en tant qu\'Administrateur.') . '
        </div>';
    
        sql_free_result($result);
    
        adminfoot('fv', '', $arg1, '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $date
     * @param [type] $title
     * @param [type] $text
     * @param [type] $reviewer
     * @param [type] $email
     * @param [type] $score
     * @param [type] $cover
     * @param [type] $url
     * @param [type] $url_title
     * @return void
     */
    public function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title)
    {
        $title      = stripslashes(FixQuotes($title));
        $text       = stripslashes(FixQuotes($text));
        $reviewer   = stripslashes(FixQuotes($reviewer));
        $email      = stripslashes(FixQuotes($email));
    
        sql_query("INSERT INTO reviews VALUES (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1')");
        sql_query("DELETE FROM reviews_add WHERE id = '$id'");
    
        Header("Location: admin.php?op=reviews");
    }

}
