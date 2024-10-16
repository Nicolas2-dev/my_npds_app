<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class Newsletter extends AdminController
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
    protected $hlpfile = 'lnl';

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
    protected $f_meta_nom = 'lnl';


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
        $this->f_titre = __d('newsletter', 'Petite Lettre D\'information');

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
     * case "User_List":
     * 
     * Undocumented function
     *
     * @return void
     */
    public function lnl_user_list()
    {
        $result = sql_query("SELECT email, date, status FROM lnl_outside_users ORDER BY date");
    
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Liste des prospects') . '</h3>
        <table id="tad_prospect" data-toggle="table" data-search="true" data-striped="true" data-mobile-responsive="true" data-show-export="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-5" data-halign="center" data-sortable="true">' . __d('newsletter', 'E-mail') . '</th>
                    <th class="n-t-col-xs-3" data-halign="center" data-align="right" data-sortable="true">' . __d('newsletter', 'Date') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center" data-sortable="true">' . __d('newsletter', 'Etat') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('newsletter', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($email, $date, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $email . '</td>
                    <td>' . $date . '</td>';
    
            if ($status == "NOK")
                echo '
                <td class="text-danger">' . $status . '</td>';
            else
                echo '
                <td class="text-success">' . $status . '</td>';
    
            echo '
                    <td><a href="admin.php?op=lnl_Sup_User&amp;lnl_user_email=' . $email . '" class="text-danger"><i class="fas fa-trash fa-lg text-danger" data-bs-toggle="tooltip" title="' . __d('newsletter', 'Effacer') . '"></i></a></td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <br /><a href="javascript:history.go(-1)" class="btn btn-secondary">' . __d('newsletter', 'Retour en arrière') . '</a>';
    
        adminfoot('', '', '', '');
    }

}
