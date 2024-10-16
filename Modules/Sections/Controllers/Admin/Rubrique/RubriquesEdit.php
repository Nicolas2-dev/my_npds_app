<?php

namespace Modules\Sections\Controllers\Admin\Rubrique;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;


class RubriquesEdit extends AdminController
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
     * @param [type] $rubid
     * @return void
     */
    public function rubriquedit($rubid)
    {
        if ($radminsuper != 1) {
            Header("Location: admin.php?op=sections");
        }
    
        $result = sql_query("SELECT rubid, rubname, intro, enligne, ordre FROM rubriques WHERE rubid='$rubid'");
        list($rubid, $rubname, $intro, $enligne, $ordre) = sql_fetch_row($result);
    
        if (!sql_num_rows($result)) {
            Header("Location: admin.php?op=sections");
        }

        $result2 = sql_query("SELECT secid FROM sections WHERE rubid='$rubid'");
    
        $number     = sql_num_rows($result2);
        $rubname    = stripslashes($rubname);
        $intro      = stripslashes($intro);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Editer une Rubrique : ') . ' <span class="text-muted">' . Language::aff_langue($rubname) . ' #' . $rubid . '</span></h3>';
        
        if ($number) {
            echo '<span class="badge bg-secondary">' . $number . '</span>&nbsp;' . __d('sections', 'sous-rubrique(s) attachée(s)');
        }
        
        echo '
                <form id="rubriquedit" action="admin.php" method="post" name="adminForm">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="rubname">' . __d('sections', 'Titre') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" id="rubname" name="rubname" maxlength ="255" rows="2" required="required">' . $rubname . '</textarea>
                    <span class="help-block text-end"><span id="countcar_rubname"></span></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="introc">' . __d('sections', 'Texte d\'introduction') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" id="introc" name="introc" rows="30" >' . $intro . '</textarea>
                    </div>
                </div>
                ' . Editeur::aff_editeur('introc', '') . '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3 pt-0" for="enligne">' . __d('sections', 'En Ligne') . '</label>';
    
        if ($radminsuper == 1) {
            if ($enligne == 1) {
                $sel1 = 'checked="checked"';
                $sel2 = '';
            } else {
                $sel1 = '';
                $sel2 = 'checked="checked"';
            }
        }
    
        echo '
                    <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="enligne_n" name="enligne" value="0" ' . $sel2 . ' />
                        <label class="form-check-label" for="enligne_n">' . __d('sections', 'Non') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="enligne_y" name="enligne" value="1" ' . $sel1 . ' />
                        <label class="form-check-label" for="enligne_y">' . __d('sections', 'Oui') . '</label>
                    </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                    <input type="hidden" name="rubid" value="' . $rubid . '" />
                    <input type="hidden" name="op" value="rubriquechange" />
                    <button class="btn btn-primary" type="submit">' . __d('sections', 'Enregistrer') . '</button>&nbsp;
                    <input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
                    </div>
                </div>
            </form>';
    
        $arg1 = '
        var formulid = ["rubriquedit"];
        inpandfieldlen("rubname",255);';
    
        Css::adminfoot('fv', '', $arg1, '');
    }

}
