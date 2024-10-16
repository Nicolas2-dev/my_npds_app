<?php

namespace Modules\ReseauxSociaux\Controllers\Front;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Users\Support\Facades\User;
use Modules\Users\Support\Facades\UserMenu;


class ReseauxSociauxEditReseaux extends FrontController
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
    public function EditReseaux()
    {
        global $cookie; 

        $res_id = array();

        if (!$user) {
            header('location:index.php');
        }
    
        $userdata = User::get_userdata_from_id($cookie[0]);

        if (file_exists("modules/ReseauxSociaux/config/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/config/reseaux-sociaux.conf.php");
        }

        $posterdata_extend = User::get_userdata_extend_from_id($cookie[0]);

        if ($posterdata_extend['M2'] != '') {
            $i = 0;

            $socialnetworks = explode(';', $posterdata_extend['M2']);

            foreach ($socialnetworks as $socialnetwork) {
                $res_id[] = explode('|', $socialnetwork);
            }

            sort($res_id);
            sort($rs);
        }

        echo '
        <h2>' . __d('reseauxsociaux', 'Utilisateur') . '</h2>';

        UserMenu::member_menu($userdata['mns'], $userdata['uname']);
        
        echo '
        <h3 class="mt-1">' . __d('reseauxsociaux', 'Réseaux sociaux') . '</h3>
        <div>
        <div class="help-block">' . __d('reseauxsociaux', 'Ajouter ou supprimer votre identifiant à ces réseaux sociaux.') . '</div>
        <hr />
        <form id="reseaux_user" action="modules.php?ModStart=' . $ModStart . '&amp;ModPath=' . $ModPath . '&amp;op=SaveSetReseaux" method="post">';

        $i = 0;
        $ident = '';

        foreach ($rs as $v1) {
            if ($res_id) {
                foreach ($res_id as $y1) {
                    $k = array_search($y1[0], $v1);

                    if (false !== $k) {
                        $ident = $y1[1];
                        break;
                    } else {
                        $ident = '';
                    }
                }
            }

            if ($i == 0) {
                echo '<div class="row">';
            }

            echo '
            <div class="col-sm-6">
                <fieldset>
                    <legend><i class="fab fa-' . $v1[2] . ' fs-1 text-primary me-2 align-middle"></i>' . $v1[0] . '</legend>
                    <div class="mb-3 form-floating">
                    <input class="form-control" type="text" id="rs_uid' . $i . '" name="rs[' . $i . '][uid]"  maxlength="50"  placeholder="' . __d('reseauxsociaux', 'Identifiant') . ' ' . $v1[0] . '" value="' . $ident . '"/>
                    <label for="rs_uid' . $i . '">' . __d('reseauxsociaux', 'Identifiant') . '</label>
                    </div>
                    <span class="help-block text-end"><span id="countcar_rs_uid' . $i . '"></span></span>
                    <input type="hidden" name="rs[' . $i . '][id]" value="' . $v1[0] . '" />
                </fieldset>
            </div>';

            if ($i % 2 == 1) {
                echo '
                </div>
                <div class="row">';

                $i++;
            }
        }

        echo '
        </div>
            <div class="my-3 row">
                <div class="col-sm-6">
                    <button class="btn btn-primary col-12" type="submit"><i class="fa fa-check fa-lg"></i>&nbsp;' . __d('reseauxsociaux', 'Sauvegarder') . '</button>
                    <input type="hidden" name="op" value="SaveSetReseaux" />
                </div>
            </div>
        </form>';

        Css::adminfoot('', '', '', '');
    }

}
