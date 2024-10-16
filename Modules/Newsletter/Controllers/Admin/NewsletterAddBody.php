<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Shared\Editeur\Support\Facades\Editeur;


/**
 * Undocumented class
 */
class NewsletterAddBody extends AdminController
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
     * Undocumented function
     *
     * @return void
     */
    public function Add_Body()
    {
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Corps de message') . '</h3>
        <form id="lnlbody" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="html">' . __d('newsletter', 'Format de données') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" id="html" type="number" min="0" max="1" step="1" value="1" name="html" required="required" />
                    <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" id="xtext" rows="30" name="xtext" ></textarea>
                    </div>
                </div>';
    
        global $tiny_mce_relurl;
    
        $tiny_mce_relurl = "false";
        echo Editeur::aff_editeur("xtext", "false");
    
        echo '
                <div class="mb-3 row">
                    <input type="hidden" name="op" value="lnl_Add_Body_Submit" />
                    <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('newsletter', 'Ajouter') . ' ' . __d('newsletter', 'corps de message') . '</button>
                    <a href="admin.php?op=lnl" class="btn btn-secondary col-sm-12 col-md-6">' . __d('newsletter', 'Retour en arrière') . '</a>
                </div>
            </fieldset>
        </form>';
    
        $fv_parametres = '
            html: {
            validators: {
                regexp: {
                    regexp:/[0-1]$/,
                    message: "0 | 1"
                }
            }
        },
        ';
    
        $arg1 = '
        var formulid = ["lnlbody"];
        ';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
