<?php

namespace Modules\Sections\Controllers\Admin\Rubrique;

use Modules\Npds\Core\AdminController;


class Rubriques extends AdminController
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

    // Fonctions RUBRIQUES
    function rubriquedit($rubid)
    {
        if ($radminsuper != 1)
            Header("Location: admin.php?op=sections");
    
        $result = sql_query("SELECT rubid, rubname, intro, enligne, ordre FROM rubriques WHERE rubid='$rubid'");
        list($rubid, $rubname, $intro, $enligne, $ordre) = sql_fetch_row($result);
    
        if (!sql_num_rows($result))
            Header("Location: admin.php?op=sections");

        $result2 = sql_query("SELECT secid FROM sections WHERE rubid='$rubid'");
    
        $number = sql_num_rows($result2);
        $rubname = stripslashes($rubname);
        $intro = stripslashes($intro);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Editer une Rubrique : ') . ' <span class="text-muted">' . aff_langue($rubname) . ' #' . $rubid . '</span></h3>';
        
        if ($number)
            echo '<span class="badge bg-secondary">' . $number . '</span>&nbsp;' . __d('sections', 'sous-rubrique(s) attachée(s)');
    
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
                ' . aff_editeur('introc', '') . '
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
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function rubriquemake($rubname, $introc)
    {
        global $radminsuper, $aid;
    
        $rubname = stripslashes(FixQuotes($rubname));
        $introc = stripslashes(FixQuotes(dataimagetofileurl($introc, 'modules/upload/upload/rub')));
    
        sql_query("INSERT INTO rubriques VALUES (NULL,'$rubname','$introc','0','0')");
    
        //mieux ? création automatique d'une sous rubrique avec droits ... ?
        if ($radminsuper != 1) {
            $result = sql_query("SELECT rubid FROM rubriques ORDER BY rubid DESC LIMIT 1");
            list($rublast) = sql_fetch_row($result);
    
            sql_query("INSERT INTO sections VALUES (NULL,'A modifier !', '', '', '$rublast', '<p>Cette sous-rubrique a été créé automatiquement. <br />Vous pouvez la personaliser et ensuite rattacher les publications que vous souhaitez.</p>','99','0')");
            
            $result = sql_query("SELECT secid FROM sections ORDER BY secid DESC LIMIT 1");
            list($seclast) = sql_fetch_row($result);
    
            droitsalacreation($aid, $seclast);
    
            Ecr_Log('security', "CreateSections(Vide) by AID : $aid (via system)", '');
        }
        //mieux ... ?
    
        Ecr_Log('security', "CreateRubriques($rubname) by AID : $aid", '');
    
        Header("Location: admin.php?op=ordremodule");
    }
    
    function rubriquechange($rubid, $rubname, $introc, $enligne)
    {
        $rubname = stripslashes(FixQuotes($rubname));
        $introc = dataimagetofileurl($introc, 'modules/upload/upload/rub');
        $introc = stripslashes(FixQuotes($introc));
    
        sql_query("UPDATE rubriques SET rubname='$rubname', intro='$introc', enligne='$enligne' WHERE rubid='$rubid'");
    
        global $aid;
        Ecr_Log("security", "UpdateRubriques($rubid, $rubname) by AID : $aid", "");
    
        Header("Location: admin.php?op=sections");
    }

    function rubriquedelete($rubid, $ok = 0)
    {
        if (!$radminsuper) {
            Header("Location: admin.php?op=sections");
        }
    
        if ($ok == 1) {
            $result = sql_query("SELECT secid FROM sections WHERE rubid='$rubid'");
    
            if (sql_num_rows($result) > 0) {
    
                while (list($secid) = sql_fetch_row($result)) {
                    $result2 = sql_query("SELECT artid FROM seccont WHERE secid='$secid'");
    
                    if (sql_num_rows($result2) > 0) {
                        while (list($artid) = sql_fetch_row($result2)) {
                            sql_query("DELETE FROM seccont WHERE artid='$artid'");
                            sql_query("DELETE FROM compatsujet WHERE id1='$artid'");
                        }
                    }
                }
            }
    
            sql_query("DELETE FROM sections WHERE rubid='$rubid'");
            sql_query("DELETE FROM rubriques WHERE rubid='$rubid'");
    
            global $aid;
            Ecr_Log("security", "DeleteRubriques($rubid) by AID : $aid", "");
    
            Header("Location: admin.php?op=sections");
        } else {

            $result = sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubid'");
            list($rubname) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la Rubrique : ') . '<span class="text-muted">' . aff_langue($rubname) . '</span></h3>
            <div class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous sûr de vouloir effacer cette Rubrique ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=rubriquedelete&amp;rubid=' . $rubid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" href="admin.php?op=sections" role="button">' . __d('sections', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
