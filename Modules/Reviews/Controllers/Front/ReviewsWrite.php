<?php

namespace Modules\Reviews\Controllers\Front;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;


class ReviewsWrite extends FrontController
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
     * @return void
     */
    public function write_review()
    {
        global $admin, $user, $cookie;
    
        echo '
        <h2>' . __d('reviews', 'Ecrire une critique') . '</h2>
        <hr />
        <form id="writereview" method="post" action="reviews.php">
            <div class="form-floating mb-3">
                <textarea class="form-control" id="title_rev" name="title" required="required" maxlength="150" style="height:70px"></textarea>
                <label for="title_rev">' . __d('reviews', 'Objet') . '</label>
                <span class="help-block text-end" id="countcar_title_rev"></span>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" id="text_rev" name="text" required="required" style="height:120px"></textarea>
                <label for="text_rev">' . __d('reviews', 'Texte') . '</label>
                <span class="help-block">' . __d('reviews', 'Attention à votre expression écrite. Vous pouvez utiliser du code html si vous savez le faire') . '</span>
            </div>';
    
        if ($user) {
            $result = sql_query("SELECT uname, email FROM users WHERE uname='$cookie[1]'");
            list($uname, $email) = sql_fetch_row($result);
    
            echo '
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="reviewer_rev" name="reviewer" value="' . $uname . '" maxlength="25" required="required" />
                <label for="reviewer_rev">' . __d('reviews', 'Votre nom') . '</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email_rev" name="email" value="' . $email . '" maxlength="254" required="required" />
                <label for="email_rev">' . __d('reviews', 'Votre adresse Email') . '</label>
                <span class="help-block text-end" id="countcar_email_rev"></span>
            </div>';
    
        } else {
            echo '
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="reviewer_rev" name="reviewer" required="required" />
                <label for="reviewer_rev">' . __d('reviews', 'Votre nom') . '</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email_rev" name="email" maxlength="254" required="required" />
                <label for="email_rev">' . __d('reviews', 'Votre adresse Email') . '</label>
                <span class="help-block text-end" id="countcar_email_rev"></span>
            </div>';
        }

        echo '
            <div class="form-floating mb-3">
                <select class="form-select" id="score_rev" name="score">
                    <option value="10">10</option>
                    <option value="9">9</option>
                    <option value="8">8</option>
                    <option value="7">7</option>
                    <option value="6">6</option>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
                <label for="score_rev">' . __d('reviews', 'Evaluation') . '</label>
                <span class="help-block">' . __d('reviews', 'Choisir entre 1 et 10 (1=nul 10=excellent)') . '</span>
            </div>';
    
        if (!Config::get('npds.short_review')) {
            echo '
            <div class="form-floating mb-3">
                <input type="url" class="form-control" id="url_rev" name="url" maxlength="320" />
                <label for="url_rev">' . __d('reviews', 'Lien relatif') . '</label>
                <span class="help-block">' . __d('reviews', 'Site web officiel. Veillez à ce que votre url commence bien par') . ' http(s)://<span class="float-end" id="countcar_url_rev"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="url_title_rev" name="url_title" maxlength="50" />
                <label for="url_title_rev">' . __d('reviews', 'Titre du lien') . '</label>
                <span class="help-block">' . __d('reviews', 'Obligatoire seulement si vous soumettez un lien relatif') . '<span class="float-end" id="countcar_url_title_rev"></span></span>
            </div>';
    
            if ($admin) {
                echo '
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="cover_rev" name="cover" maxlength="50" />
                    <label for="cover_rev">' . __d('reviews', 'Nom de fichier de l\'image') . '</label>
                    <span class="help-block">' . __d('reviews', 'Nom de l\'image principale non obligatoire, la mettre dans images/reviews/') . '<span class="float-end" id="countcar_cover_rev"></span></span>
                </div>';
            }
        }
    
        echo '
            <input type="hidden" name="op" value="preview_review" />
            <button type="submit" class="btn btn-primary my-3 me-2" >' . __d('reviews', 'Prévisualiser') . '</button>
            <button onclick="history.go(-1)" class="btn btn-secondary my-3">' . __d('reviews', 'Retour en arrière') . '</button>
            <p class="help-block">' . __d('reviews', 'Assurez-vous de l\'exactitude de votre information avant de la communiquer. N\'écrivez pas en majuscules, votre texte serait automatiquement rejeté') . '</p>
        </form>';
    
        $arg1 = '
            var formulid = ["writereview"];
            inpandfieldlen("title_rev",150);
            inpandfieldlen("email_rev",254);
            inpandfieldlen("url_rev",320);
            inpandfieldlen("url_title_rev",50);
            inpandfieldlen("cover_rev",100);';
    
        Css::adminfoot('fv', '', $arg1, 'foo');
    }

}
