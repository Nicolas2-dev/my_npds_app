<?php

namespace Modules\Sections\Controllers\Admin\Order;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class OrderCours extends AdminController
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
    public function ordrecours()
    {
        global $secid;
    
        if ($radminsuper <> 1) {
            Header("Location: admin.php?op=sections");
        }

        $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
        list($secname) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Changer l\'ordre') . ' ' . __d('sections', 'des') . ' ' . __d('sections', 'publications') . ' / ' . Language::aff_langue($secname) . '</h3>
        <form id="ordrecours" action="admin.php" method="post" name="adminForm">
            <table class="table table-borderless table-sm table-hover table-striped">
                <thead>
                    <tr>
                    <th data-sortable="true" class="n-t-col-xs-2">' . __d('sections', 'Index') . '</th>
                    <th data-sortable="true" class="n-t-col-xs-10">' . __d('sections', 'Publications') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT artid, title, ordre FROM seccont WHERE secid='$secid' ORDER BY ordre");
        $numrow = sql_num_rows($result);
    
        $i = 0;
        $fv_parametres = '';
    
        while (list($artid, $title, $ordre) = sql_fetch_row($result)) {
            $i++;
    
            echo '
                    <tr>
                    <td>
                        <div class="mb-3 mb-0">
                            <input type="hidden" name="artid[' . $i . ']" value="' . $artid . '" />
                            <input type="text" class="form-control" id="ordre' . $i . '" name="ordre[' . $i . ']" value="' . $ordre . '"  maxlength="4" required="required" />
                        </div>
                    </td>
                    <td><label class="col-form-label" for="ordre' . $i . '">' . Language::aff_langue($title) . '</label></td>
                    </tr>';
    
            $fv_parametres .= '
                "ordre[' . $i . ']": {
                validators: {
                    regexp: {
                    regexp:/^\d{1,4}$/,
                    message: "0-9"
                    },
                    between: {
                    min: 1,
                    max: ' . $numrow . ',
                    message: "1 ... ' . $numrow . '"
                    }
                }
            },';
        }
    
        echo '
                </tbody>
            </table>
            <div class="mb-3 mt-3">
                <input type="hidden" name="op" value="majcours" />
                <input type="submit" class="btn btn-primary" value="' . __d('sections', 'Valider') . '" />
                <input type="button" class="btn btn-secondary" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
            </div>
        </form>';
    
        $arg1 = '
            var formulid = ["ordrecours"];';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
