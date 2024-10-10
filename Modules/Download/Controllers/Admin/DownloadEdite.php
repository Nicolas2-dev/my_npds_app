<?php

namespace Modules\download\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class DownloadEdite extends AdminController
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
     * @param [type] $did
     * @return void
     */
    public function DownloadEdit($did)
    {
        global $hlpfile, $f_meta_nom, $f_titre;

        $result = sql_query("SELECT did, dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM downloads WHERE did='$did'");
        list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $privs) = sql_fetch_row($result);
        
        $ddescription = stripslashes($ddescription);

        echo '
        <hr />
        <h3 class="mb-3">' . __d('download', 'Editer un Téléchargement') . '</h3>
        <form action="admin.php" method="post" id="downloaded" name="adminForm">
            <input type="hidden" name="did" value="' . $did . '" />
            <input type="hidden" name="dcounter" value="' . $dcounter . '" />
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="durl">' . __d('download', 'Télécharger URL') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="durl" name="durl" value="' . $durl . '" maxlength="320" required="required" />
                    <span class="help-block text-end" id="countcar_durl"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dfilename">' . __d('download', 'Nom de fichier') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dfilename" name="dfilename" id="dfilename" value="' . $dfilename . '" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_dfilename"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dver">' . __d('download', 'Version') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="dver" id="dver" value="' . $dver . '" maxlength="6" />
                    <span class="help-block text-end" id="countcar_dver"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dfilesize">' . __d('download', 'Taille de fichier') . ' (bytes)</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dfilesize" name="dfilesize" value="' . $dfilesize . '" maxlength="31" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dweb">' . __d('download', 'Propriétaire de la page Web') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dweb" name="dweb" value="' . $dweb . '" maxlength="255" />
                    <span class="help-block text-end" id="countcar_dweb"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="duser">' . __d('download', 'Propriétaire') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="duser" name="duser" value="' . $duser . '" maxlength="30" />
                    <span class="help-block text-end" id="countcar_duser"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="dcategory">' . __d('download', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="dcategory" name="dcategory" value="' . stripslashes($dcategory) . '" maxlength="250" required="required" />
                    <span class="help-block text-end"><span id="countcar_dcategory"></span></span>
                    <select class="form-select" name="sdcategory" onchange="adminForm.dcategory.value=options[selectedIndex].value">';

        $result = sql_query("SELECT DISTINCT dcategory FROM downloads ORDER BY dcategory");
        
        while (list($Xdcategory) = sql_fetch_row($result)) {
            $sel = $Xdcategory == $dcategory ? 'selected' : '';
            $Xdcategory = stripslashes($Xdcategory);
            echo '<option ' . $sel . ' value="' . $Xdcategory . '">' . aff_langue($Xdcategory) . '</option>';
        }

        echo '
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="xtext">' . __d('download', 'Description') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" id="xtext" name="xtext" rows="20" >' . $ddescription . '</textarea>
                </div>
            </div>
            ' . aff_editeur('xtext', '');

        echo '
            <fieldset>
                <legend>' . __d('download', 'Droits') . '</legend>';

        droits($privs);

        echo '
            </fieldset>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4">' . __d('download', 'Changer la date') . '</label>
                <div class="col-sm-8">
                    <div class="form-check my-2">
                    <input type="checkbox" id="ddate" name="ddate" class="form-check-input" value="yes" />
                    <label class="form-check-label" for="ddate">' . __d('download', 'Oui') . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input type="hidden" name="op" value="DownloadSave" />
                    <input class="btn btn-primary" type="submit" value="' . __d('download', 'Sauver les modifications') . '" />
                </div>
            </div>
        </form>';

        $arg1 = '
            var formulid = ["downloaded"];
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
