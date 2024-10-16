<?php

namespace Modules\Sections\Controllers\Admin\Order;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class OrderModule extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
    public function ordremodule()
    {
        if ($radminsuper <> 1) {
            Header("Location: admin.php?op=sections");
        }

        // data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons-prefix="fa" data-icons="icons"
        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Changer l\'ordre des rubriques') . '</h3>
        <form action="admin.php" method="post" id="ordremodule" name="adminForm">
            <table class="table table-borderless table-sm table-hover table-striped">
                <thead>
                    <tr>
                    <th data-sortable="true" class="n-t-col-xs-2">' . __d('sections', 'Index') . '</th>
                    <th data-sortable="true" class="n-t-col-xs-10">' . __d('sections', 'Rubriques') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT rubid, rubname, ordre FROM rubriques ORDER BY ordre");
        $numrow = sql_num_rows($result);
    
        $i = 0;
        $fv_parametres = '';
    
        while (list($rubid, $rubname, $ordre) = sql_fetch_row($result)) {
            $i++;
    
            echo '<tr>
                    <td>
                        <div class="mb-3 mb-0">
                            <input type="hidden" name="rubid[' . $i . ']" value="' . $rubid . '" />
                            <input type="text" class="form-control" id="ordre' . $i . '" name="ordre[' . $i . ']" value="' . $ordre . '" maxlength="4" required="required" />
                        </div>
                    </td>
                    <td><label class="col-form-label" for="ordre' . $i . '">' . Language::aff_langue($rubname) . '</label></td>
                </tr>';
    
            $fv_parametres .= '
                "ordre[' . $i . ']": {
                validators: {
                    regexp: {
                    regexp:/^\d{1,4}$/,
                    message: "0-9"
                    }
                }
            },';
        }
    
        echo '
                </tbody>
            </table>
            <div class="mb-3 mt-3">
                <input type="hidden" name="i" value="' . $i . '" />
                <input type="hidden" name="op" value="majmodule" />
                <button type="submit" class="btn btn-primary" >' . __d('sections', 'Valider') . '</button>
                <button class="btn btn-secondary" onclick="javascript:history.back()" >' . __d('sections', 'Retour en arrière') . '</button>
            </div>
        </form>';
    
        $arg1 = '
            var formulid = ["ordremodule"];';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
