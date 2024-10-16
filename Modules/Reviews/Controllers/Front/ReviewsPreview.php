<?php

namespace Modules\Reviews\Controllers\Front;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Hack;
use Modules\Npds\Support\Facades\Spam;
use Modules\Npds\Support\Facades\Mailer;
use Modules\Npds\Support\Facades\Language;
use Modules\Reviews\Support\Facades\Reviews as LReviews;


class ReviewsPreview extends FrontController
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
     * @param [type] $title
     * @param [type] $text
     * @param [type] $reviewer
     * @param [type] $email
     * @param [type] $score
     * @param [type] $cover
     * @param [type] $url
     * @param [type] $url_title
     * @param [type] $hits
     * @param [type] $id
     * @return void
     */
    public function preview_review($title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $hits, $id)
    {
        global $admin;
    
        $title      = stripslashes(strip_tags($title));
        $text       = stripslashes(Hack::remove(conv2br($text)));
        $reviewer   = stripslashes(strip_tags($reviewer));
        $url_title  = stripslashes(strip_tags($url_title));
        $error      = '';
    
        echo '<h2 class="mb-4">';
        echo $id != 0 ? __d('reviews', 'Modification d\'une critique') : __d('reviews', 'Ecrire une critique');
        echo '
        </h2>
        <form id="prevreview" method="post" action="reviews.php">';
    
        if ($title == '') {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Titre non valide... Il ne peut pas être vide') . '</div>';
        }
    
        if ($text == '') {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Texte de critique non valide... Il ne peut pas être vide') . '</div>';
        }
    
        if (($score < 1) || ($score > 10)) {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Note non valide... Elle doit se situer entre 1 et 10') . '</div>';
        }
    
        if (($hits < 0) && ($id != 0)) {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Le nombre de hits doit être un entier positif') . '</div>';
        }
    
        if ($reviewer == '' || $email == '') {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Vous devez entrer votre nom et votre adresse Email') . '</div>';

        } else if ($reviewer != '' && $email != '') {
            if (!preg_match('#^[_\.0-9a-z-]+@[0-9a-z-\.]+\.+[a-z]{2,4}$#i', $email)) {
                $error = 1;
                echo '<div class="alert alert-danger">' . __d('reviews', 'Email non valide (ex.: prenom.nom@hotmail.com)') . '</div>';
            }
    
            if (Mailer::checkdnsmail($email) === false) {
                $error = 1;
                echo '<div class="alert alert-danger">' . __d('reviews', 'Erreur : DNS ou serveur de mail incorrect') . '</div>';
            }
        }
    
        if ((($url_title != '' && $url == '') || ($url_title == "" && $url != "")) and (!Config::get('npds.short_review'))) {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Vous devez entrer un titre de lien et une adresse relative, ou laisser les deux zones vides') . '</div>';

        } elseif (($url != "") && (!preg_match('#^http(s)?://#i', $url))) {
            $error = 1;
            echo '<div class="alert alert-danger">' . __d('reviews', 'Site web officiel. Veillez à ce que votre url commence bien par') . ' http(s)://</div>';
        }
    
        if ($error == 1) {
            echo '<button class="btn btn-secondary" type="button" onclick="history.go(-1)"><i class="fa fa-lg fa-undo"></i></button>';
        } else {
            $fdate = date(str_replace('%', '', __d('reviews', 'linksdatestring')), time() + ((int) Config::get('npds.gmt') * 3600));
    
            echo __d('reviews', 'Critique');
    
            echo '
            <br />' . __d('reviews', 'Ajouté :') . ' ' . $fdate . '
            <hr />
            <h3>' . stripslashes($title) . '</h3>';
    
            if ($cover != '') {
                echo '<img class="img-fluid" src="assets/images/reviews/' . $cover . '" alt="img_" />';
            }

            echo $text;
    
            echo '
            <hr />
            <strong>' . __d('reviews', 'Le critique') . ' :</strong> <a href="mailto:' . $email . '" target="_blank">' . $reviewer . '</a><br />
            <strong>' . __d('reviews', 'Note') . '</strong>
            <span class="text-success">';
    
            LReviews::display_score($score);
    
            echo '</span>';
    
            if ($url != '') {
                echo '<br /><strong>' . __d('reviews', 'Lien relatif') . ' :</strong> <a href="' . $url . '" target="_blank">' . $url_title . '</a>';
            }

            if ($id != 0) {
                echo '<br /><strong>' . __d('reviews', 'ID de la critique') . ' :</strong> ' . $id . '<br />
                <strong>' . __d('reviews', 'Hits') . ' :</strong> ' . $hits . '<br />';
            }
    
            $text = urlencode($text);
    
            echo '
                <input type="hidden" name="id" value="' . $id . '" />
                <input type="hidden" name="hits" value="' . $hits . '" />
                <input type="hidden" name="date" value="' . $fdate . '" />
                <input type="hidden" name="title" value="' . $title . '" />
                <input type="hidden" name="text" value="' . $text . '" />
                <input type="hidden" name="reviewer" value="' . $reviewer . '" />
                <input type="hidden" name="email" value="' . $email . '" />
                <input type="hidden" name="score" value="' . $score . '" />
                <input type="hidden" name="url" value="' . $url . '" />
                <input type="hidden" name="url_title" value="' . $url_title . '" />
                <input type="hidden" name="cover" value="' . $cover . '" />
                <input type="hidden" name="op" value="add_reviews" />
                <p class="my-3">' . __d('reviews', 'Cela semble-t-il correct ?') . '</p>';
    
            if (!$admin) {
                echo Spam::Q_spambot();
            }
    
            $consent = '[french]Pour conna&icirc;tre et exercer vos droits notamment de retrait de votre consentement &agrave; l\'utilisation des donn&eacute;es collect&eacute;es veuillez consulter notre <a href="static.php?op=politiqueconf.html&amp;App=1&amp;metalang=1">politique de confidentialit&eacute;</a>.[/french][english]To know and exercise your rights, in particular to withdraw your consent to the use of the data collected, please consult our <a href="static.php?op=politiqueconf.html&amp;App=1&amp;metalang=1">privacy policy</a>.[/english][spanish]Para conocer y ejercer sus derechos, en particular para retirar su consentimiento para el uso de los datos recopilados, consulte nuestra <a href="static.php?op=politiqueconf.html&amp;App=1&amp;metalang=1">pol&iacute;tica de privacidad</a>.[/spanish][german]Um Ihre Rechte zu kennen und auszu&uuml;ben, insbesondere um Ihre Einwilligung zur Nutzung der erhobenen Daten zu widerrufen, konsultieren Sie bitte unsere <a href="static.php?op=politiqueconf.html&amp;App=1&amp;metalang=1">Datenschutzerkl&auml;rung</a>.[/german][chinese]&#x8981;&#x4E86;&#x89E3;&#x5E76;&#x884C;&#x4F7F;&#x60A8;&#x7684;&#x6743;&#x5229;&#xFF0C;&#x5C24;&#x5176;&#x662F;&#x8981;&#x64A4;&#x56DE;&#x60A8;&#x5BF9;&#x6240;&#x6536;&#x96C6;&#x6570;&#x636E;&#x7684;&#x4F7F;&#x7528;&#x7684;&#x540C;&#x610F;&#xFF0C;&#x8BF7;&#x67E5;&#x9605;&#x6211;&#x4EEC;<a href="static.php?op=politiqueconf.html&#x26;App=1&#x26;metalang=1">&#x7684;&#x9690;&#x79C1;&#x653F;&#x7B56;</a>&#x3002;[/chinese]';
            $accept = "[french]En soumettant ce formulaire j'accepte que les informations saisies soient exploit&#xE9;es dans le cadre de l'utilisation et du fonctionnement de ce site.[/french][english]By submitting this form, I accept that the information entered will be used in the context of the use and operation of this website.[/english][spanish]Al enviar este formulario, acepto que la informaci&oacute;n ingresada se utilizar&aacute; en el contexto del uso y funcionamiento de este sitio web.[/spanish][german]Mit dem Absenden dieses Formulars erkl&auml;re ich mich damit einverstanden, dass die eingegebenen Informationen im Rahmen der Nutzung und des Betriebs dieser Website verwendet werden.[/german][chinese]&#x63D0;&#x4EA4;&#x6B64;&#x8868;&#x683C;&#x5373;&#x8868;&#x793A;&#x6211;&#x63A5;&#x53D7;&#x6240;&#x8F93;&#x5165;&#x7684;&#x4FE1;&#x606F;&#x5C06;&#x5728;&#x672C;&#x7F51;&#x7AD9;&#x7684;&#x4F7F;&#x7528;&#x548C;&#x64CD;&#x4F5C;&#x8303;&#x56F4;&#x5185;&#x4F7F;&#x7528;&#x3002;[/chinese]";
            
            echo '
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="consent" name="consent" value="1" required="required"/>
                        <label class="form-check-label" for="consent">'
                    . Language::aff_langue($accept) . '
                            <span class="text-danger"> *</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input class="btn btn-primary" type="submit" value="' . __d('reviews', 'Oui') . '" />&nbsp;
                    <input class="btn btn-secondary" type="button" onclick="history.go(-1)" value="' . __d('reviews', 'Non') . '" />
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col small" >' . Language::aff_langue($consent) . '
                </div>
            </div>';
    
            if ($id != 0) {
                $word = __d('reviews', 'modifié');
            } else {
                $word = __d('reviews', 'ajouté');
            }
    
            if ($admin) {
                echo '<div class="alert alert-success"><strong>' . __d('reviews', 'Note :') . '</strong> ' . __d('reviews', 'Actuellement connecté en administrateur... Cette critique sera') . ' ' . $word . ' ' . __d('reviews', 'immédiatement') . '.</div>';
            }
        }
    
        echo '</form>';
    
        $arg1 = '
        var formulid = ["prevreview"];';
    
        Css::adminfoot('fv', '', $arg1, 'foo');
    }

}
