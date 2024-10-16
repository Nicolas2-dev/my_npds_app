<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Shared\Editeur\Support\Facades\Editeur;


/**
 * Undocumented class
 */
class NewsletterAddHeaderFooter extends AdminController
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
     * case "Add_Footer": => Add_Header_Footer("FOT");
     * case "Add_Header": => Add_Header_Footer("HED");
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function Add_Header_Footer($ibid)
    {
        $t = '';
        $v = '';
    
        if ($ibid == 'HED') {
            $ti = "message d'entête";
            $va = 'lnl_Add_Header_Submit';
        } else {
            $ti = "Message de pied de page";
            $va = 'lnl_Add_Footer_Submit';
        }
    
        echo '
            <hr />
            <h3 class="mb-2">' . ucfirst(__d('newsletter', $ti)) . '</h3>
            <form id="lnlheadfooter" action="admin.php" method="post" name="adminForm">
            <fieldset>
                <div class="mb-3">
                    <label class="col-form-label" for="html">' . __d('newsletter', 'Format de données') . '</label>
                    <div>
                        <input class="form-control" id="html" type="number" min="0" max="1" value="1" name="html" required="required" />
                        <span class="help-block"> <code>html</code> ==&#x3E; [1] / <code>text</code> ==&#x3E; [0]</span>
                    </div>
                    </div>
                <div class="mb-3">
                    <label class="col-form-label" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                    <div>
                    <textarea class="form-control" id="xtext" rows="20" name="xtext" ></textarea>
                    </div>
                </div>
                <div class="mb-3">';
    
        global $tiny_mce_relurl;
    
        $tiny_mce_relurl = 'false';
        echo Editeur::aff_editeur('xtext', 'false');
    
        echo '
                    <input type="hidden" name="op" value="' . $va . '" />
                    <button class="btn btn-primary col-sm-12 col-md-6" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('newsletter', 'Ajouter') . ' ' . __d('newsletter', $ti) . '</button>
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
        var formulid = ["lnlheadfooter"];
        ';
    
        Css::adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
}
