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
     * Undocumented function
     *
     * @return void
     */
    public function main()
    {
        echo '
        <hr />
        <h3 class="mb-2">' . __d('newsletter', 'Petite Lettre D\'information') . '</h3>
        <ul class="nav flex-md-row flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="admin.php?op=lnl_List">' . __d('newsletter', 'Liste des LNL envoyées') . '</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin.php?op=lnl_User_List">' . __d('newsletter', 'Afficher la liste des prospects') . '</a>
            </li>
        </ul>
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Header" ><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Message d\'entête') . '</h4>';
        
        ShowHeader();
    
        echo '
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Body" ><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Corps de message') . '</h4>';
        
        ShowBody();
    
        echo '
        <h4 class="my-3"><a href="admin.php?op=lnl_Add_Footer"><i class="fa fa-plus-square me-2"></i></a>' . __d('newsletter', 'Message de pied de page') . '</h4>';
        
        ShowFooter();
    
        echo '
        <hr />
        <h4>' . __d('newsletter', 'Assembler une lettre et la tester') . '</h4>
        <form id="ltesto" action="admin.php" method="post">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xheader" id="testXheader"min="0" />
                    <label for="testXheader">' . __d('newsletter', 'Entête') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xbody" id="testXbody" maxlength="11" />
                    <label for="testXbody">' . __d('newsletter', 'Corps') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xfooter" id="testXfooter" min="0" />
                    <label for="testXfooter">' . __d('newsletter', 'Pied') . '</label>
                    </div>
                </div>
                <div class="mb-3 col-sm-12">
                    <input type="hidden" name="op" value="lnl_Test" />
                    <button class="btn btn-primary" type="submit">' . __d('newsletter', 'Valider') . '</button>
                </div>
            </div>
        </form>
        <hr />
        <h4>' . __d('newsletter', 'Envoyer La Lettre') . '</h4>
        <form id="lsendo" action="admin.php" method="post">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xheader" id="Xheader" />
                    <label for="Xheader">' . __d('newsletter', 'Entête') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xbody" id="Xbody" min="0" />
                    <label for="Xbody">' . __d('newsletter', 'Corps') . '</label>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="number" name="Xfooter" id="Xfooter" />
                    <label for="Xfooter">' . __d('newsletter', 'Pied') . '</label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-floating mb-3">
                    <input class="form-control" type="text" maxlength="255" id="Xsubject" name="Xsubject" />
                    <label for="Xsubject">' . __d('newsletter', 'Sujet') . '</label>
                    <span class="help-block text-end"><span id="countcar_Xsubject"></span></span>
                    </div>
                </div>
                <hr />
                <div class="mb-3 col-sm-12">
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="All" checked="checked" id="tous" name="Xtype" />
                    <label class="form-check-label" for="tous">' . __d('newsletter', 'Tous les Utilisateurs') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="Mbr" id="mem" name="Xtype" />
                    <label class="form-check-label" for="mem">' . __d('newsletter', 'Seulement aux membres') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" value="Out" id="prosp" name="Xtype" />
                    <label class="form-check-label" for="prosp">' . __d('newsletter', 'Seulement aux prospects') . '</label>
                    </div>
                </div>';
    
        $mX = liste_group();
        $tmp_groupe = '';
    
        foreach ($mX as $groupe_id => $groupe_name) {
            if ($groupe_id == '0') 
                $groupe_id = '';
    
            $tmp_groupe .= '<option value="' . $groupe_id . '">' . $groupe_name . '</option>';
        }
    
        echo '
                <div class="mb-3 col-sm-12">
                    <select class="form-select" name="Xgroupe">' . $tmp_groupe . '</select>
                </div>
                <input type="hidden" name="op" value="lnl_Send" />
                <div class="mb-3 col-sm-12">
                    <button class="btn btn-primary" type="submit">' . __d('newsletter', 'Valider') . '</button>
                </div>
            </div>
            </form>';
    
        $fv_parametres = '
                Xbody: {
                validators: {
                    regexp: {
                    regexp:/^\d{1,11}$/,
                    message: "0 | 1"
                    }
                }
            },
            ';
    
        $arg1 = '
            var formulid = ["ltesto","lsendo"];
            inpandfieldlen("Xsubject",255);
            ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
