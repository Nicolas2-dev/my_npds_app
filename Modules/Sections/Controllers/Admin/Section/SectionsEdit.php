<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Sections\Support\Facades\Section;


class SectionsEdit extends AdminController
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
     * @param [type] $secid
     * @return void
     */
    public function sectionedit($secid)
    {
        $result = sql_query("SELECT secid, secname, image, userlevel, rubid, intro FROM sections WHERE secid='$secid'");
        list($secid, $secname, $image, $userlevel, $rubref, $intro) = sql_fetch_row($result);
    
        $secname    = stripslashes($secname);
        $intro      = stripslashes($intro);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Sous-rubrique') . ' : <span class="text-muted">' . Language::aff_langue($secname) . '</span></h3>';
    
        $result2 = sql_query("SELECT artid FROM seccont WHERE secid='$secid'");
    
        $number = sql_num_rows($result2);
    
        if ($number) {
            echo '<span class="badge bg-secondary p-2 me-2">' . $number . ' </span>' . __d('sections', 'publication(s) attachée(s)');
        }

        echo '
                <form id="sectionsedit" action="admin.php" method="post" name="adminForm">
                <div class="mb-3">
                    <label class="col-form-label" for="rubref">' . __d('sections', 'Rubriques') . '</label>';
    
    
        if ($radminsuper == 1) {
            $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
        } else {
            $result = sql_query("SELECT DISTINCT r.rubid, r.rubname FROM rubriques r LEFT JOIN sections s on r.rubid= s.rubid LEFT JOIN publisujet p on s.secid= p.secid2 WHERE p.aid='$aid'");
        }

        echo '<select class="form-select" id="rubref" name="rubref">';
    
        while (list($rubid, $rubname) = sql_fetch_row($result)) {
            $sel = $rubref == $rubid ? 'selected="selected"' : '';
    
            echo '<option value="' . $rubid . '" ' . $sel . '>' . Language::aff_langue($rubname) . '</option>';
        }
    
        echo '
                    </select>
            </div>';
    
        // ici on a(vait) soit le select qui permet de changer la sous rubrique de rubrique (ca c'est good) soit un input caché avec la valeur fixé de la rubrique...donc ICI un author ne peut pas changer sa sous rubrique de rubrique ...il devrait pouvoir le faire dans une sous-rubrique ou il a des "droits" ??
     
        // if ($radminsuper==1) {
        //     echo '<select class="form-select" id="rubref" name="rubref">';

        //     $result = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");

        //     while(list($rubid, $rubname) = sql_fetch_row($result)) {
        //         $sel = $rubref == $rubid ? 'selected="selected"' : '';

        //         echo '<option value="'. $rubid .'" '. $sel .'>'. Language::aff_langue($rubname) .'</option>';
        //         }
        //     echo '
        //             </select>
        //     </div>';
        // } else {
        //     echo '<input type="hidden" name="rubref" value="'. $rubref .'" />';
        //     $result = sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubref'");

        //     list($rubname) = sql_fetch_row($result);
        //     echo '<pan class="ms-2">'. Language::aff_langue($rubname) .'</span>';
        // }
        
        //ici
        echo '
        <div class="mb-3">
            <label class="col-form-label" for="secname">' . __d('sections', 'Sous-rubrique') . '</label>
            <textarea class="form-control" id="secname" name="secname" rows="4" maxlength="255" required="required">' . $secname . '</textarea>
            <span class="help-block text-end"><span id="countcar_secname"></span></span>
        </div>
        <div class="mb-3">
            <label class="col-form-label" for="image">' . __d('sections', 'Image') . '</label>
            <input type="text" class="form-control" id="image" name="image" maxlength="255" value="' . $image . '" />
            <span class="help-block text-end"><span id="countcar_image"></span></span>
        </div>
        <div class="mb-3">
            <label class="col-form-label" for="introd">' . __d('sections', 'Texte d\'introduction') . '</label>
            <textarea class="tin form-control" id="introd" name="introd" rows="20">' . $intro . '</textarea>
        </div>';
    
        echo Editeur::aff_editeur('introd', '');
    
        Section::droits($userlevel);
    
        $droit_pub = Section::droits_publication($secid);
    
        if ($droit_pub == 3 or $droit_pub == 7) {
            echo '<input type="hidden" name="secid" value="' . $secid . '" />
                <input type="hidden" name="op" value="sectionchange" />
                <button class="btn btn-primary" type="submit">' . __d('sections', 'Enregistrer') . '</button>';
        }
    
        echo '
        <input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
        </form>';
    
        $arg1 = '
        var formulid = ["sectionsedit"];
        inpandfieldlen("secname",255);
        inpandfieldlen("image",255);
        ';
    
        Css::adminfoot('fv', '', $arg1, '');
    }

}
