<?php

namespace Modules\ReseauxSociaux\Controllers\Front;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Hack;
use Modules\Users\Support\Facades\User;
use Modules\Users\Support\Facades\UserMenu;


class ReseauxSociaux extends FrontController
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
    public function ListReseaux()
    {
        global $userdata, $cookie;

        if (!$user) {
            header('location:index.php');
        }
    
        $userdata = User::get_userdata_from_id($cookie[0]);

        if (file_exists("modules/ReseauxSociaux/config/reseaux-sociaux.conf.php")) {
            include("modules/ReseauxSociaux/config/reseaux-sociaux.conf.php");
        }

        echo '
        <h2>' . __d('reseauxsociaux', 'Utilisateur') . '</h2>
        ' . UserMenu::member_menu($userdata['mns'], $userdata['uname']) . '
        <h3 class="mt-3">' . __d('reseauxsociaux', 'Réseaux sociaux') . '</h3>
        <div class="help-block">' . __d('reseauxsociaux', 'Liste des réseaux sociaux mis à disposition par l\'administrateur.') . '</div>
        <hr />
        <h3><a href="modules.php?ModPath=ReseauxSociaux&amp;ModStart=' . $ModStart . '&amp;op=EditReseaux"><i class="fa fa-edit fa-lg"></i></a>&nbsp;' . __d('reseauxsociaux', 'Editer') . '</h3>
        <div class="row mt-3">';

        foreach ($rs as $v1) {
            echo '
            <div class="col-sm-3 col-6">
                <div class="card mb-4">
                    <div class="card-body text-center">
                    <i class="fab fa-' . $v1[2] . ' fa-2x text-primary"></i></br>' . $v1[0] . '
                    </div>
                </div>
            </div>';
        }

        echo '
        </div>';
    }

}
