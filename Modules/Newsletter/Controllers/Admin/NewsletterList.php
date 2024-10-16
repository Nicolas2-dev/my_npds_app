<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class NewsletterList extends AdminController
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
     * case "List":
     * 
     * Undocumented function
     *
     * @return void
     */
    public function lnl_list()
    {
        $result = sql_query("SELECT ref, header , body, footer, number_send, type_send, date, status FROM lnl_send ORDER BY date");
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('newsletter', 'Liste des LNL envoyées') . '</h3>
        <table data-toggle="table" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">ID</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Entête') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Corps') . '</th>
                    <th class="n-t-col-xs-1" data-halign="center" data-align="right">' . __d('newsletter', 'Pied') . '</th>
                    <th data-halign="center" data-align="right">' . __d('newsletter', 'Nbre d\'envois effectués') . '</th>
                    <th data-halign="center" data-align="center">' . __d('newsletter', 'Type') . '</th>
                    <th data-halign="center" data-align="right">' . __d('newsletter', 'Date') . '</th>
                    <th data-halign="center" data-align="center">' . __d('newsletter', 'Etat') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        while (list($ref, $header, $body, $footer, $number_send, $type_send, $date, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $ref . '</td>
                    <td>' . $header . '</td>
                    <td>' . $body . '</td>
                    <td>' . $footer . '</td>
                    <td>' . $number_send . '</td>
                    <td>' . $type_send . '</td>
                    <td>' . $date . '</td>';
    
            if ($status == "NOK") {
                echo '
                <td class="text-danger">' . $status . '</td>';
            } else {
                echo '
                <td>' . $status . '</td>';
            }
    
            echo '
                </tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        Css::adminfoot('', '', '', '');
    }

}
