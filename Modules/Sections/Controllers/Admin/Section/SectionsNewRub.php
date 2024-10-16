<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Sections\Support\Facades\Section;


class SectionsNewRub extends AdminController
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
     * @param [type] $type
     * @return void
     */
    public function new_rub_section($type)
    {
        $arg1 = '';
    
        if ($type == 'sec') {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('sections', 'Ajouter une nouvelle Sous-Rubrique') . '</h3>
            <form action="admin.php" method="post" id="newsection" name="adminForm">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="rubref">' . __d('sections', 'Rubriques') . '</label>
                    <div class="col-sm-8">
                    <select class="form-select" id="rubref" name="rubref">';
    
            if ($radminsuper == 1) {
                $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
            } else {
                $result = sql_query("SELECT DISTINCT r.rubid, r.rubname FROM rubriques r LEFT JOIN sections s on r.rubid= s.rubid LEFT JOIN publisujet p on s.secid= p.secid2 WHERE p.aid='$aid'");
            }

            while (list($rubid, $rubname) = sql_fetch_row($result)) {
                echo '<option value="' . $rubid . '">' . Language::aff_langue($rubname) . '</option>';
            }
    
            echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4 col-md-4" for="image">' . __d('sections', 'Image pour la Sous-Rubrique') . '</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="image" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="secname">' . __d('sections', 'Titre') . '</label>
                    <textarea  class="form-control" id="secname" name="secname" maxlength="255" rows="2" required="required"></textarea>
                    <span class="help-block text-end"><span id="countcar_secname"></span></span>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="introd">' . __d('sections', 'Texte d\'introduction') . '</label>
                    <textarea class="tin form-control" name="introd" rows="30"></textarea>';
    
            echo Editeur::aff_editeur("introd", '');
    
            echo '</div>';
    
            Section::droits("0");
    
            echo '
            <div class="mb-3">
                <input type="hidden" name="op" value="sectionmake" />
                <button class="btn btn-primary col-sm-6 col-12 col-md-4" type="submit" /><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('sections', 'Ajouter') . '</button>
                <button class="btn btn-secondary col-sm-6 col-12 col-md-4" type="button" onclick="javascript:history.back()">' . __d('sections', 'Retour en arrière') . '</button>
            </div>
            </form>';
    
            $arg1 = '
                var formulid = ["newsection"];
                inpandfieldlen("secname",255);';
        } elseif ($type == "rub") {
            echo '
                <hr />
                <h3 class="mb-3">' . __d('sections', 'Ajouter une nouvelle Rubrique') . '</h3>
                <form action="admin.php" method="post" id="newrub" name="adminForm">
                    <div class="mb-3">
                    <label class="col-form-label" for="rubname">' . __d('sections', 'Nom de la Rubrique') . '</label>
                    <textarea class="form-control" id="rubname" name="rubname" rows="2" maxlength="255" required="required"></textarea>
                    <span class="help-block text-end" id="countcar_rubname"></span>
                    </div>
                    <div class="mb-3">
                    <label class="col-form-label" for="introc">' . __d('sections', 'Texte d\'introduction') . '</label>
                    <textarea class="tin form-control" id="introc" name="introc" rows="30" ></textarea>
                    </div>';
    
            echo Editeur::aff_editeur('introc', '');
    
            echo '
                    <div class="mb-3">
                    <input type="hidden" name="op" value="rubriquemake" />
                    <button class="btn btn-primary" type="submit"><i class="fa fa-plus-square fa-lg"></i>&nbsp;' . __d('sections', 'Ajouter') . '</button>
                    <button class="btn btn-secondary" type="button" onclick="javascript:history.back()">' . __d('sections', 'Retour en arrière') . '</button>
                    </div>
                </form>';
    
            $arg1 = '
                var formulid = ["newrub"];
                inpandfieldlen("rubname",255);';
        }
    
        Css::adminfoot('fv', '', $arg1, '');
    }

}
