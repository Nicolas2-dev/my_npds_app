<?php

namespace App\Modules\Banners\Controllers\Front;

use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class BannerclientLogin extends FrontController
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
    public function clientlogin()
    {
        header_page();
    
        echo '
            <div class="card card-body mb-3">
                <h3 class="mb-4"><i class="fas fa-sign-in-alt fa-lg me-3 align-middle"></i>' . __d('banners', 'Connexion') . '</h3>
                <form id="loginbanner" action="banners.php" method="post">
                    <fieldset>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="text" id="login" name="login" maxlength="10" required="required" />
                        <label for="login">' . __d('banners', 'Identifiant') . '</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" type="password" id="pass" name="pass" maxlength="10" required="required" />
                        <label for="pass">' . __d('banners', 'Mot de passe') . '</label>
                        <span class="help-block">' . __d('banners', 'Merci de saisir vos informations') . '</span>
                    </div>
                    <input type="hidden" name="op" value="Ok" />
                    <button class="btn btn-primary my-3" type="submit">' . __d('banners', 'Valider') . '</button>
                    </div>
                    </fieldset>
                </form>
            </div>';
    
        $arg1 = 'var formulid=["loginbanner"];';
    
        Css::adminfoot('fv', '', $arg1, 'no');
    
        footer_page();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function IncorrectLogin()
    {
        header_page();
        echo '<div class="alert alert-danger lead">
                ' . __d('banners', 'Identifiant incorrect !') . '
                <br />
                <button class="btn btn-secondary mt-2" onclick="javascript:history.go(-1)" >
                    ' . __d('banners', 'Retour en arrière') . '
                </button>
            </div>';
        footer_page();
    }

}
