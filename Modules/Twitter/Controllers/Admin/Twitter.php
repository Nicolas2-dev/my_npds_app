<?php

namespace Modules\Twitter\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class Twitter extends AdminController
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
    protected $hlpfile = 'admtwi';

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
    protected $f_meta_nom = 'npds_twi';


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
        $this->f_titre = __d('twitter', 'npds_twitter');

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
     * @param [type] $subop
     * @param [type] $ModPath
     * @param [type] $ModStart
     * @param [type] $class_sty_2
     * @param [type] $npds_twi_arti
     * @param [type] $npds_twi_urshort
     * @param [type] $npds_twi_post
     * @param [type] $consumer_key
     * @param [type] $consumer_secret
     * @param [type] $oauth_token
     * @param [type] $oauth_token_secret
     * @param [type] $tbox_width
     * @param [type] $tbox_height
     * @return void
     */
    public function Configuretwi($subop, $ModPath, $ModStart, $class_sty_2, $npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height)
    {
        // if (file_exists('modules/' . $ModPath . '/twi_conf.php')) {
        //     include('modules/' . $ModPath . '/twi_conf.php');
        // }

        $checkarti_y = '';
        $checkarti_n = '';
        $checkpost_y = '';
        $checkpost_n = '';
        $urshort_mr = '';
        $urshort_ft = '';
        $urshort_c = '';

        if ($npds_twi_arti === 1) {
            $checkarti_y = 'checked="checked"';
        } else {
            $checkarti_n = 'checked="checked"';
        }

        if ($npds_twi_post === 1) {
            $checkpost_y = 'checked="checked"';
        } else {
            $checkpost_n = 'checked="checked"';
        }

        if ($npds_twi_urshort === 1) {
            $urshort_mr = 'checked="checked"';
        }

        if ($npds_twi_urshort === 2) {
            $urshort_ft = 'checked="checked"';
        }

        if ($npds_twi_urshort === 3) {
            $urshort_c = 'checked="checked"';
        } else {
            $checkpost_n = 'checked="checked"';
        }

        echo '<hr />';

        if (Config::get('npds.npds_twi') !== 1) {
            echo '<div class="alert alert-danger">' . __d('twister', 'La publication de vos news sur twitter n\'est pas autorisée vous devez l\'activer') . ' <a class="alert-link" href="admin.php?op=Configure">' . __d('twister', 'Ici') . '</a></div>';
        } else {
            echo '<div class="alert alert-success">' . __d('twister', 'La publication de vos news sur twitter est autorisée. Vous pouvez révoquer cette autorisation') . ' <a class="alert-link" href="admin.php?op=Configure">' . __d('twister', 'Ici') . '</a></div>';
        }

        echo '
        <h3 class="mb-3">' . __d('twister', 'Configuration du module App_twi') . '</h3>
        <span class="text-danger">*</span> ' . __d('twister', 'requis') . '
        <form id="twitterset" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="App_twi_arti">' . __d('twister', 'Activation de la publication auto des articles') . '</label>
                <div class="col-sm-6 my-2">
                    <div class="form-check">
                    <input class="form-check-input" type="radio" id="App_twi_arti_y" name="App_twi_arti" value="1" ' . $checkarti_y . ' />
                    <label class="form-check-label" for="App_twi_arti_y">' . __d('twister', 'Oui') . '</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" id="App_twi_arti_n" name="App_twi_arti" value="0" ' . $checkarti_n . ' />
                    <label class="form-check-label" for="App_twi_arti_n">' . __d('twister', 'Non') . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="form-label col-sm-6" for="App_twi_urshort">' . __d('twister', 'Méthode pour le raccourciceur d\'URL') . '</label>
                <div class="col-sm-6">
                    <div class="custom-controls-stacked">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="App_twi_mod" name="App_twi_urshort" value="1" ' . $urshort_mr . ' />
                        <label class="form-check-label" for="App_twi_mod">' . __d('twister', 'Réécriture d\'url avec mod_rewrite') . '</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="App_twi_force" name="App_twi_urshort" value="2" ' . $urshort_ft . ' />
                        <label class="form-check-label" for="App_twi_force">' . __d('twister', 'Réécriture d\'url avec ForceType') . '</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="App_twi_npd" name="App_twi_urshort" value="3" ' . $urshort_c . ' />
                        <label class="form-check-label" for="App_twi_npd">' . __d('twister', 'Réécriture d\'url avec contrôleur App') . '</label>
                    </div>
                    </div>
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="consumer_key" name="consumer_key" value="' . $consumer_key . '" required="required" />
                <label for="consumer_key">' . __d('twister', 'Votre clef de consommateur') . '&nbsp;<span class="text-danger">*</span></label>
                <span class="help-block small">' . $consumer_key . '</span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="consumer_secret" name="consumer_secret" value="' . $consumer_secret . '" required="required" />
                <label for="consumer_secret">' . __d('twister', 'Votre clef secrète de consommateur') . '&nbsp;<span class="text-danger">*</span></label>
                <span class="help-block small">' . $consumer_secret . '</span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="oauth_token" name="oauth_token" value="' . $oauth_token . '" required="required" />
                <label for="oauth_token" >' . __d('twister', 'Jeton d\'accès pour Open Authentification (oauth_token)') . '&nbsp;<span class="text-danger">*</span></label>
                <span class="help-block small">' . $oauth_token . '</span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="oauth_token_secret" name="oauth_token_secret" value="' . $oauth_token_secret . '" />
                <label for="oauth_token_secret" >' . __d('twister', 'Jeton d\'accès secret pour Open Authentification (oauth_token_secret)') . ' <span class="text-danger">*</span></label>
                <span class="help-block small">' . $oauth_token_secret . '</span>
            </div>
            <!--
            <tr>
            <td colspan="2"><strong>' . __d('twister', 'Interface bloc') . '</strong></td>
            </tr>
            <td width="30%">
            ' . __d('twister', 'Largeur de la tweet box') . ' <span class="text-danger">*</span> : ' . $tbox_width . '
            </td>
            <td>
            <input type="text" " size="25" maxlength="3" name="tbox_width" value="' . $tbox_width . '" />
            </td>
            </tr>
            <tr>
            <td width="30%">
            ' . __d('twister', 'Hauteur de la tweet box') . '</span>  <span class="text-danger">*</span> : ' . $tbox_height . '
            </td>
            <td>
            <input type="text" " size="25" maxlength="3" name="tbox_height" value="' . $tbox_height . '" />
            </td>
            </tr>
            <tr>
            <td colspan="2"><strong>Styles</strong></td>
            </tr>
            <tr>
            <td width="30%">
            <span class="' . $class_sty_2 . '">' . __d('twister', 'Classe de style titre') . '</span> </td><td><input type="text" size="25" maxlength="255" name="class_sty_1" value="' . $class_sty_1 . '">
            </td>
            </tr>
            <tr>
            <td width="30%">
            <span class="' . $class_sty_2 . '">' . __d('twister', 'Classe de style sous-titre') . '</span>
            </td>
            <td>
            <input type="text" size="25" maxlength="255" name="class_sty_2" value="' . $class_sty_2 . '" />
            </td>
            </tr>
            -->';

        echo '
            <input class="btn btn-primary my-3" type="submit" value="' . __d('twister', 'Enregistrez') . '" />
            <input type="hidden" name="op" value="Extend-Admin-SubModule" />
            <input type="hidden" name="ModPath" value="' . $ModPath . '" />
            <input type="hidden" name="ModStart" value="' . $ModStart . '" />
            <input type="hidden" name="subop" value="SaveSettwi" />
        </form>
        <div class="text-end">Version : ' . $npds_twi_versus . '</div>';

        $arg1 = '
            var formulid = ["twitterset"];';

        Css::adminfoot('fv', '', $arg1, '');
    }

}
