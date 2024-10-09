<?php

namespace App\Modules\Banners\Controllers\Admin;


use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;

/**
 * Undocumented class
 */
class BannerClientEdite extends AdminController
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
    protected $hlpfile = 'banners';

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
    protected $f_meta_nom = 'BannersAdmin';


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
        $this->f_titre = __d('banners', 'Administration des bannières');

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
     * @param [type] $cid
     * @return void
     */
    public function BannerClientEdit($cid)
    {
        $result = sql_query("SELECT name, contact, email, login, passwd, extrainfo FROM bannerclient WHERE cid='$cid'");
        list($name, $contact, $email, $login, $passwd, $extrainfo) = sql_fetch_row($result);
    
        echo '
            <hr />
            <h3 class="mb-3">' . __d('banners', 'Editer l\'annonceur') . '</h3>
            <form action="admin.php" method="post" id="bannersedanno">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="name" name="name" value="' . $name . '" maxlength="60" required="required" />
                    <label for="name">' . __d('banners', 'Nom de l\'annonceur') . '</label>
                    <span class="help-block text-end"><span id="countcar_name"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="contact" name="contact" value="' . $contact . '" maxlength="60" required="required" />
                    <label for="contact">' . __d('banners', 'Nom du Contact') . '</label>
                    <span class="help-block text-end"><span id="countcar_contact"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="email" id="email" name="email" maxlength="254" value="' . $email . '" required="required" />
                    <label for="email">' . __d('banners', 'E-mail') . '</label>
                    <span class="help-block text-end"><span id="countcar_email"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="login" name="login" maxlength="10" value="' . $login . '" required="required" />
                    <label for="login">' . __d('banners', 'Identifiant') . '</label>
                    <span class="help-block text-end"><span id="countcar_login"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" id="passwd" name="passwd" maxlength="20" value="' . $passwd . '" required="required" />
                    <label for="passwd">' . __d('banners', 'Mot de Passe') . '</label>
                    <span class="help-block text-end"><span id="countcar_passwd"></span></span>
                    <div class="progress" style="height: 0.4rem;">
                        <div id="passwordMeter_cont" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="extrainfo" name="extrainfo" style="height:140px">' . $extrainfo . '</textarea>
                    <label for="extrainfo">' . __d('banners', 'Informations supplémentaires') . '</label>
                </div>
                <input type="hidden" name="cid" value="' . $cid . '" />
                <input type="hidden" name="op" value="BannerClientChange" />
                <input class="btn btn-primary my-3" type="submit" value="' . __d('banners', 'Modifier annonceur') . '" />
            </form>';
    
        $arg1 = '
            var formulid = ["bannersedanno"];
            inpandfieldlen("name",60);
            inpandfieldlen("contact",60);
            inpandfieldlen("email",254);
            inpandfieldlen("login",10);
            inpandfieldlen("passwd",20);
        ';
    
        $fv_parametres = '
        passwd: {
            validators: {
                checkPassword: {},
            }
        },';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
