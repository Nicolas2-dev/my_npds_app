<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class PollboothCreatePoll extends AdminController
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
     * case 'create': => poll_createPoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_createPoll()
    {
        global $id;
    
        echo '
            <hr />
                <h3 class="mb-3">' . __d('pollbooth', 'Liste des sondages') . '</h3>
                <table id="tad_pool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">ID</th>
                    <th data-sortable="true" data-halign="center">' . __d('pollbooth', 'Intitulé du Sondage') . '</th>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">' . __d('pollbooth', 'Vote') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('pollbooth', 'Fonctions') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT pollID, pollTitle, voters FROM poll_desc ORDER BY timeStamp");
        
        while ($object = sql_fetch_assoc($result)) {
            echo '
                    <tr>
                    <td>' . $object["pollID"] . '</td>
                    <td>' . aff_langue($object["pollTitle"]) . '</td>
                    <td>' . $object["voters"] . '</td>
                    <td>
                        <a href="admin.php?op=editpollPosted&amp;id=' . $object["pollID"] . '"><i class="fa fa-edit fa-lg" title="' . __d('pollbooth', 'Editer ce sondage') . '" data-bs-toggle="tooltip"></i></a>
                        <a href="admin.php?op=removePosted&amp;id=' . $object["pollID"] . '"><i class="fas fa-trash fa-lg text-danger ms-2" title="' . __d('pollbooth', 'Effacer ce sondage') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                    </tr>';
    
            $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM poll_data WHERE pollID='" . $object["pollID"] . "'");
            list($sum) = sql_fetch_row($result2);
        }
    
        echo '
                </tbody>
            </table>
            <hr />
            <h3 class="mb-3">' . __d('pollbooth', 'Créer un nouveau Sondage') . '</h3>
            <form id="pollssondagenew" action="admin.php" method="post">
                <input type="hidden" name="op" value="createPosted" />
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="pollTitle" name="pollTitle" id="pollTitle" maxlength="100" required="required" />
                    <label for="pollTitle">' . __d('pollbooth', 'Intitulé du Sondage') . '</label>
                    <span class="help-block">' . __d('pollbooth', 'S.V.P. entrez chaque option disponible dans un seul champ') . '</span>
                    <span class="help-block text-end"><span id="countcar_pollTitle"></span></span>
                </div>';
    
        $requi = '';
    
        for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
            $requi = $i < 3 ? ' required="required" ' : '';
    
            echo '
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="optionText' . $i . '" name="optionText[' . $i . ']" maxlength="255" ' . $requi . ' />
                    <label for="optionText' . $i . '">' . __d('pollbooth', 'Option') . ' ' . $i . '</label>
                    <span class="help-block text-end"><span id="countcar_optionText' . $i . '"></span></span>
                </div>';
        }
    
        echo '
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" id="poll_type" name="poll_type" value="1" />
                    <label class="form-check-label" for="poll_type">' . __d('pollbooth', 'Seulement aux membres') . '</label>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">' . __d('pollbooth', 'Créer') . '</button>
                </div>
            </form>';
    
        $arg1 = '
        var formulid = ["pollssondagenew"];
        inpandfieldlen("pollTitle",100)';
    
        for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
            $arg1 .= '
            inpandfieldlen("optionText' . $i . '",255)';
        }
    
        Css::adminfoot('fv', '', $arg1, '');
    }
    
}
