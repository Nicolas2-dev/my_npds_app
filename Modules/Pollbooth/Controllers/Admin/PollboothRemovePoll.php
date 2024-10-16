<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class PollboothRemovePoll extends AdminController
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
    protected $hlpfile = 'surveys';

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
    protected $f_meta_nom = 'create';


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
        $this->f_titre = __d('pollbooth', 'Les sondages');

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
     * case 'remove': => poll_removePoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_removePoll()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('pollbooth', 'Retirer un Sondage existant') . '</h3>
        <span class="help-block">' . __d('pollbooth', 'S.V.P. Choisissez un sondage dans la liste suivante.') . '</span>
        <p align="center"><span class="text-danger">' . __d('pollbooth', 'ATTENTION : Le Sondage choisi va être supprimé IMMEDIATEMENT de la base de données !') . '</span></p>
        ';
    
        echo '
        <form action="admin.php" method="post">
            <input type="hidden" name="op" value="removePosted" />
            <table id="tad_delepool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                    <th></th>
                    <th data-sortable="true">' . __d('pollbooth', 'Intitulé du Sondage') . '</th>
                    <th data-sortable="true">ID</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT pollID, pollTitle FROM poll_desc ORDER BY timeStamp");
    
        while ($object = sql_fetch_assoc($result)) {
            echo '
                    <tr>
                    <td><input type="radio" name="id" value="' . $object['pollID'] . '" /></td>
                    <td> ' . $object['pollTitle'] . '</td>
                    <td>ID : ' . $object['pollID'] . '</td>
                    </tr>
            ';
        }
    
        echo '
                </tbody>
            </table>
            <br />
            <div class="mb-3">
                <button class="btn btn-danger" type="submit">' . __d('pollbooth', 'Retirer') . '</button>
            </div>
        </form>';
    
        // include('footer.php');
    }

}
