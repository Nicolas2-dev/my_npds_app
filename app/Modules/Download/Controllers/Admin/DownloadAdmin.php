<?php

namespace App\Modules\download\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class DownloadAdmin extends AdminController
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
    protected $hlpfile = 'downloads';

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
    protected $f_meta_nom = 'DownloadAdmin';


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
        $this->f_titre = __d('download', 'Téléchargements');

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
    public function DownloadAdmin()
    {
        global $hlpfile, $f_meta_nom, $f_titre;

        $resultX = sql_query("SELECT DISTINCT dcategory FROM downloads ORDER BY dcategory");
        $num_row = sql_num_rows($resultX);

        echo '
        <hr />
        <h3 class="my-3">' . __d('download', 'Catégories') . '</h3>';

        $pseudocatid = '';

        while (list($dcategory) = sql_fetch_row($resultX)) {
            $pseudocatid++;
            echo '
            <h4 class="mb-2"><a class="tog" id="show_cat_' . $pseudocatid . '" title="Déplier la liste"><i id="i_cat_' . $pseudocatid . '" class="fa fa-caret-down fa-lg text-primary"></i></a>
                ' . aff_langue(stripslashes($dcategory)) . '</h4>';

            echo '
            <div class="mb-3" id="cat_' . $pseudocatid . '" style="display:none;">
                <table data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
                    <thead>
                        <tr>
                        <th data-sortable="true" data-halign="center" data-align="right">' . __d('download', 'ID') . '</th>
                        <th data-sortable="true" data-halign="center" data-align="right">' . __d('download', 'Compteur') . '</th>
                        <th data-sortable="true" data-halign="center" data-align="center">' . __d('download', 'Typ.') . '</th>
                        <th data-halign="center" data-align="center">' . __d('download', 'URL') . '</th>
                        <th data-sortable="true" data-halign="center" >' . __d('download', 'Nom de fichier') . '</th>
                        <th data-halign="center" data-align="center">' . __d('download', 'Version') . '</th>
                        <th data-halign="center" data-align="right">' . __d('download', 'Taille de fichier') . '</th>
                        <th data-halign="center" >' . __d('download', 'Date') . '</th>
                        <th data-halign="center" data-align="center">' . __d('download', 'Fonctions') . '</th>
                        </tr>
                    </thead>
                    <tbody>';

            $result = sql_query("SELECT did, dcounter, durl, dfilename, dfilesize, ddate, dver, perms FROM downloads WHERE dcategory='" . addslashes($dcategory) . "' ORDER BY did ASC");
            
            while (list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dver, $dperm) = sql_fetch_row($result)) {

                if ($dperm == '0') 
                    $dperm = '<span title="' . __d('download', 'Anonymes') . '<br />' . __d('download', 'Membres') . '<br />' . __d('download', 'Administrateurs') . '" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"><i class="far fa-user fa-lg"></i><i class="fas fa-user fa-lg"></i><i class="fa fa-user-cog fa-lg"></i></span>';
                else if ($dperm == '1') 
                    $dperm = '<span title="' . __d('download', 'Membres') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-user fa-lg"></i></span>';
                else if ($dperm == '-127') 
                    $dperm = '<span title="' . __d('download', 'Administrateurs') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-user-cog fa-lg"></i></span>';
                else if ($dperm == '-1') 
                    $dperm = '<span title="' . __d('download', 'Anonymes') . '"  data-bs-toggle="tooltip" data-bs-placement="right"><i class="far fa-user fa-lg"></i></span>';
                else 
                    $dperm = '<span title="' . __d('download', 'Groupes') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-users fa-lg"></i></span>';
                
                echo '
                    <tr>
                    <td>' . $did . '</td>
                    <td>' . $dcounter . '</td>
                    <td>' . $dperm . '</td>
                    <td><a href="' . $durl . '" title="' . __d('download', 'Téléchargements') . '<br />' . $durl . '" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true"><i class="fa fa-download fa-2x"></i></a></td>
                    <td>' . $dfilename . '</td>
                    <td><span class="small">' . $dver . '</span></td>
                    <td><span class="small">';

                $Fichier = new FileManagement;

                if ($dfilesize != 0)
                    echo $Fichier->file_size_format($dfilesize, 1);
                else
                    echo $Fichier->file_size_auto($durl, 2);

                echo '</span></td>
                    <td class="small">' . $ddate . '</td>
                    <td>
                        <a href="admin.php?op=DownloadEdit&amp;did=' . $did . '" title="' . __d('download', 'Editer') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-edit fa-lg"></i></a>
                        <a href="admin.php?op=DownloadDel&amp;did=' . $did . '&amp;ok=0" title="' . __d('download', 'Effacer') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-trash fa-lg text-danger ms-2"></i></a>
                    </td>
                    </tr>';
            }

            echo '
                    </tbody>
                </table>
            </div>';

            echo '
            <script type="text/javascript">
                //<![CDATA[
                    $( document ).ready(function() {
                        tog("cat_' . $pseudocatid . '","show_cat_' . $pseudocatid . '","hide_cat_' . $pseudocatid . '");
                    })
                //]]>
            </script>';
        }

        echo '
        <hr />
        <h3 class="mb-3">' . __d('download', 'Ajouter un Téléchargement') . '</h3>
        <form action="admin.php" method="post" id="downloadadd" name="adminForm">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="durl">' . __d('download', 'Télécharger URL') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="durl" name="durl" maxlength="320" required="required" />
        &nbsp;<a href="javascript:void(0);" onclick="window.open(\'admin.php?op=FileManagerDisplay\', \'wdir\', \'width=650, height=450, menubar=no, location=no, directories=no, status=no, copyhistory=no, toolbar=no, scrollbars=yes, resizable=yes\');">
        <span class="">[' . __d('download', 'Parcourir') . ']</span></a>
                    <span class="help-block text-end" id="countcar_durl"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dcounter">' . __d('download', 'Compteur') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="number" id="dcounter" name="dcounter" maxlength="30" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dfilename">' . __d('download', 'Nom de fichier') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" id="dfilename" name="dfilename" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_dfilename"></span>
                    </div>
                </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dver">' . __d('download', 'Version') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="dver" id="dver" maxlength="6" />
                    <span class="help-block text-end" id="countcar_dver"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dfilesize">' . __d('download', 'Taille de fichier') . ' (bytes)</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dfilesize" name="dfilesize" maxlength="31" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dweb">' . __d('download', 'Propriétaire de la page Web') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dweb" name="dweb" maxlength="255" />
                    <span class="help-block text-end" id="countcar_dweb"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="duser">' . __d('download', 'Propriétaire') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="duser" name="duser" maxlength="30" />
                    <span class="help-block text-end" id="countcar_duser"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dcategory">' . __d('download', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dcategory" name="dcategory" maxlength="250" required="required"/>
                    <span class="help-block text-end" id="countcar_dcategory"></span>
                    <select class="form-select" name="sdcategory" onchange="adminForm.dcategory.value=options[selectedIndex].value">
                    <option>' . __d('download', 'Catégorie') . '</option>';

        $result = sql_query("SELECT DISTINCT dcategory FROM downloads ORDER BY dcategory");

        while (list($dcategory) = sql_fetch_row($result)) {
            $dcategory = stripslashes($dcategory);
            echo '<option value="' . $dcategory . '">' . aff_langue($dcategory) . '</option>';
        }

        echo '
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="xtext">' . __d('download', 'Description') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" id="xtext" name="xtext" rows="20" ></textarea>
                </div>
            </div>
            ' . aff_editeur('xtext', '') . '
            <fieldset>
                <legend>' . __d('download', 'Droits') . '</legend>';

        droits('0');

        echo '
            </fieldset>
            <input type="hidden" name="op" value="DownloadAdd" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button class="btn btn-primary" type="submit">' . __d('download', 'Ajouter') . '</button>
                </div>
            </div>
        </form>';

        $arg1 = '
                var formulid = ["downloadadd"];
                inpandfieldlen("durl",320);
                inpandfieldlen("dfilename",255);
                inpandfieldlen("dver",6);
                inpandfieldlen("dfilesize",31);
                inpandfieldlen("dweb",255);
                inpandfieldlen("duser",30);
                inpandfieldlen("dcategory",250);
        ';

        adminfoot('fv', '', $arg1, '');
    }

}
