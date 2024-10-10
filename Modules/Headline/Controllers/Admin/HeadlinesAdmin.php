<?php

namespace Modules\Headline\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class HeadlinesAdmin extends AdminController
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
    protected $hlpfile = 'headlines';

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
    protected $f_meta_nom = 'HeadlinesAdmin';


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
        $this->f_titre = __d('headline', 'Grands Titres de sites de News');

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
    public function HeadlinesAdmin()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('headline', 'Liste des Grands Titres de sites de News') . '</h3>
        <table id="tad_headline" data-toggle="table" data-classes="table table-striped table-borderless" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">' . __d('headline', 'ID') . '</th>
                <th data-sortable="true" data-halign="center" >' . __d('headline', 'Nom du site') . '</th>
                <th data-sortable="true" data-halign="center" >' . __d('headline', 'URL') . '</th>
                <th data-sortable="true" data-halign="center" data-align="right" >' . __d('headline', 'Etat') . '</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="center" >' . __d('headline', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT hid, sitename, url, headlinesurl, status FROM headlines ORDER BY hid");
    
        while (list($hid, $sitename, $url, $headlinesurl, $status) = sql_fetch_row($result)) {
            echo '
                <tr>
                    <td>' . $hid . '</td>
                    <td>' . $sitename . '</td>
                    <td>' . $url . '</td>';
    
            if ($status == 1) {
                $status = '<span class="text-success">' . __d('headline', 'Actif(s)') . '</span>';
            } else {
                $status = '<span class="text-danger">' . __d('headline', 'Inactif(s)') . '</span>';
            }
    
            echo '
                    <td>' . $status . '</td>
                    <td>
                    <a href="admin.php?op=HeadlinesEdit&amp;hid=' . $hid . '"><i class="fa fa-edit fa-lg" title="' . __d('headline', 'Editer') . '" data-bs-toggle="tooltip"></i></a>&nbsp;
                    <a href="' . $url . '" target="_blank"><i class="fas fa-external-link-alt fa-lg" title="' . __d('headline', 'Visiter') . '" data-bs-toggle="tooltip"></i></a>&nbsp;
                    <a href="admin.php?op=HeadlinesDel&amp;hid=' . $hid . '&amp;ok=0" class="text-danger"><i class="fas fa-trash fa-lg" title="' . __d('headline', 'Effacer') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                </tr>';
        }
    
        echo '
            </tbody>
        </table>
        <hr />
        <h3 class="mb-3">' . __d('headline', 'Nouveau Grand Titre') . '</h3>
        <form id="fad_newheadline" action="admin.php" method="post">
            <fieldset>
                <div class="form-floating mb-3">
                    <input id="xsitename" class="form-control" type="text" name="xsitename" placeholder="' . __d('headline', 'Nom du site') . '" maxlength="30" required="required" />
                    <label for="xsitename">' . __d('headline', 'Nom du site') . '</label>
                </div>
                <div class="form-floating mb-3">
                    <input id="url" class="form-control" type="url" name="url" placeholder="' . __d('headline', 'URL') . '" maxlength="320" required="required" />
                    <label for="url">' . __d('headline', 'URL') . '</label>
                    <span class="help-block text-end"><span id="countcar_url"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <input id="headlinesurl" class="form-control" type="url" name="headlinesurl" placeholder="' . __d('headline', 'URL pour le fichier RDF/XML') . '" maxlength="320" required="required" />
                    <label for="headlinesurl">' . __d('headline', 'URL pour le fichier RDF/XML') . '</label>
                    <span class="help-block text-end"><span id="countcar_headlinesurl"></span></span>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="status" name="status">
                    <option name="status" value="1">' . __d('headline', 'Actif(s)') . '</option>
                    <option name="status" value="0" selected="selected">' . __d('headline', 'Inactif(s)') . '</option>
                    </select>
                    <label class="col-form-label col-sm-4" for="status">' . __d('headline', 'Etat') . '</label>
                </div>
                <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg me-2"></i>' . __d('headline', 'Ajouter') . '</button>
                <input type="hidden" name="op" value="HeadlinesAdd" />
            </fieldset>
        </form>';
    
        $arg1 = '
            var formulid = ["fad_newheadline"];
            inpandfieldlen("xsitename",30);
            inpandfieldlen("url",320);
            inpandfieldlen("headlinesurl",320);';
    
        adminfoot('fv', '', $arg1, '');
    }

}
