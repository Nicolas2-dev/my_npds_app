<?php

namespace Modules\Sections\Controllers\Admin\Author;

use Modules\Npds\Core\AdminController;


class Author extends AdminController
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

    function publishrights($author)
    {
        if ($radminsuper != 1)
            Header("Location: admin.php?op=sections");

        echo '
        <hr />
        <h3 class="mb-3"><i class="fa fa-user-edit me-2"></i>' . __d('sections', 'Droits des auteurs') . ' : <span class="text-muted">' . $author . '</span></h3>
        <form action="admin.php" method="post">';
    
        $result1 = sql_query("SELECT rubid, rubname FROM rubriques ORDER BY ordre");
        $numrow = sql_num_rows($result1);
    
        $i = 0;
        $scrr = '';
        $scrsr = '';
    
        while (list($rubid, $rubname) = sql_fetch_row($result1)) {
            echo '
                <table class="table table-bordered table-sm" data-toggle="" data-classes=""  data-striped="true" data-icons-prefix="fa" data-icons="icons">
                    <thead class="thead-light">
                    <tr class="table-secondary"><th colspan="5"><span class="form-check"><input class="form-check-input" id="ckbrall_' . $rubid . '" type="checkbox" /><label class="form-check-label lead" for="ckbrall_' . $rubid . '">' . aff_langue($rubname) . '</label></span></th></tr>
                    <tr class="">
                        <th class="colspan="2" n-t-col-xs-3" data-sortable="true">' . __d('sections', 'Sous-rubriques') . '</th>
                        <th class="n-t-col-xs-2 text-center" data-halign="center" data-align="center">' . __d('sections', 'Créer') . '</th>
                        <th class="n-t-col-xs-2 text-center" data-halign="center" data-align="center">' . __d('sections', 'Publier') . '</th>
                        <th class="n-t-col-xs-2 text-center" data-halign="center" data-align="center">' . __d('sections', 'Modifier') . '</th>
                        <th class="n-t-col-xs-2 text-center" data-halign="center" data-align="center">' . __d('sections', 'Supprimer') . '</th>
                    </tr>
                    </thead>
                    <tbody>';
    
            $scrr .= '
                    $("#ckbrall_' . $rubid . '").change(function(){
                        $(".ckbr_' . $rubid . '").prop("checked", $(this).prop("checked"));
                    });';
    
            $result2 = sql_query("SELECT secid, secname FROM sections WHERE rubid='$rubid' ORDER BY ordre");
    
            while (list($secid, $secname) = sql_fetch_row($result2)) {
    
                $result3 = sql_query("SELECT type FROM publisujet WHERE secid2='$secid' AND aid='$author'");
    
                $i++;
                $crea = '';
                $publi = '';
                $modif = '';
                $supp = '';
    
                if (sql_num_rows($result3) > 0) {
                    while (list($type) = sql_fetch_row($result3)) {
                        if ($type == 1) {
                            $crea = 'checked="checked"';
                        } else if ($type == 2) {
                            $publi = 'checked="checked"';
                        } else if ($type == 3) {
                            $modif = 'checked="checked"';
                        } else if ($type == 4) {
                            $supp = 'checked="checked"';
                        }
                    }
                }
    
                echo '
                    <tr>
                        <td><div class="form-check"><input class="form-check-input" id="ckbsrall_' . $secid . '" type="checkbox" /><label class="form-check-label" for="ckbsrall_' . $secid . '">' . aff_langue($secname) . '</label></div></td>
                        <td class="text-center"><div class="form-check"><input class="form-check-input ckbsr_' . $secid . ' ckbr_' . $rubid . '" type="checkbox" id="creation' . $i . '" name="creation[' . $i . ']" value="' . $secid . '" ' . $crea . ' /><label class="form-check-label" for="creation' . $i . '"></label></div></td>
                        <td class="text-center"><div class="form-check"><input class="form-check-input ckbsr_' . $secid . ' ckbr_' . $rubid . '" type="checkbox" id="publication' . $i . '" name="publication[' . $i . ']" value="' . $secid . '" ' . $publi . ' /><label class="form-check-label" for="publication' . $i . '"></label></div></td>
                        <td class="text-center"><div class="form-check"><input class="form-check-input ckbsr_' . $secid . ' ckbr_' . $rubid . '" type="checkbox" id="modification' . $i . '" name="modification[' . $i . ']" value="' . $secid . '" ' . $modif . ' /><label class="form-check-label" for="modification' . $i . '"></label></div></td>
                        <td class="text-center"><div class="form-check"><input class="form-check-input ckbsr_' . $secid . ' ckbr_' . $rubid . '" type="checkbox" id="suppression' . $i . '" name="suppression[' . $i . ']" value="' . $secid . '" ' . $supp . ' /><label class="form-check-label" for="suppression' . $i . '"></label></div></td>
                    </tr>';
    
                $scrsr .= '
                    $("#ckbsrall_' . $secid . '").change(function(){
                        $(".ckbsr_' . $secid . '").prop("checked", $(this).prop("checked"));
                    });';
            }
    
            echo '
                    </tbody>
                </table>
            <br />';
        }
    
        echo '<input type="hidden" name="chng_aid" value="' . $author . '" />
                <input type="hidden" name="op" value="updatedroitauteurs" />
                <input type="hidden" name="maxindex" value="' . $i . '" />
                <input class="btn btn-primary me-3" type="submit" value="' . __d('sections', 'Valider') . '" />
                <input class="btn btn-secondary" type="button" onclick="javascript:history.back()" value="' . __d('sections', 'Retour en arrière') . '" />
        </form>';
    
        echo '
        <script type="text/javascript">
        //<![CDATA[
            $(document).ready(function(){
            ' . $scrr . $scrsr . '
            });
        //]]>
        </script>';
    
        adminfoot('', '', '', '');
    }
    
    function updaterights($chng_aid, $maxindex, $creation, $publication, $modification, $suppression)
    {
        if ($radminsuper != 1)
            Header("Location: admin.php?op=sections");
    
        $result = sql_query("DELETE FROM publisujet WHERE aid='$chng_aid'");
    
        for ($j = 1; $j < ($maxindex + 1); $j++) {
            if (array_key_exists($j, $creation))
                if ($creation[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$creation[$j]','1')");
                }
    
            if (array_key_exists($j, $publication))
                if ($publication[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$publication[$j]','2')");
                }
    
            if (array_key_exists($j, $modification))
                if ($modification[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$modification[$j]','3')");
                }
    
            if (array_key_exists($j, $suppression))
                if ($suppression[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$suppression[$j]','4')");
                }
        }
    
        global $aid;
        Ecr_Log('security', "UpdateRightsPubliSujet($chng_aid) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }

}
