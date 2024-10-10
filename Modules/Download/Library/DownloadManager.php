<?php

namespace Modules\Download\Library;

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Language;
use Modules\Download\Contracts\DownloadInterface;

/**
 * Undocumented class
 */
class DownloadManager implements DownloadInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [topdownload_data description]
     *
     * @param   [type]  $form   [$form description]
     * @param   [type]  $ordre  [$ordre description]
     *
     * @return  [type]          [return description]
     */
    public function topdownload_data($form, $ordre)
    {
        global $long_chain;
    
        if (!$long_chain) {
            $long_chain = 13;
        }

        $lugar = 1;
        $download_data = '';

        foreach (DB::table('downloads')
                    ->select('did', 'dcounter', 'dfilename', 'dcategory', 'ddate', 'perms')
                    ->orderBy($ordre, 'desc')
                    ->offset(Config::get('npds.top'))
                    ->limit(0)
                    ->get() as $download) 
        {
            if ($download['dcounter'] > 0) {
                $okfile = Auth::autorisation($download['dperm']);
    
                if ($ordre == 'dcounter') {
                    $download_date = Sanitize::wrh($download['dcounter']);
                }
    
                if ($ordre == 'ddate') {
                    $download_date = str_replace(
                        [
                            'd', 'm', 'Y', 'H:i'
                        ],
                        [
                            substr($download['ddate'], 8, 2), substr($download['ddate'], 5, 2), substr($download['ddate'], 0, 4), ''
                        ],
                        'd-m-Y H:i'
                    );
                }
    
                $ori_dfilename = $download['dfilename'];
    
                if (strlen($download['dfilename']) > $long_chain) {
                    $dfilename = (substr($download['dfilename'], 0, $long_chain)) . " ...";
                }
    
                if ($form == 'short') {
                    if ($okfile) {
                        $download_data[] = [
                            'lugar'         => $lugar,
                            'id'            => $download['did'],
                            'filename'      => $dfilename,
                            'ori_dfilename' => $ori_dfilename,
                            'download_date' => $download_date,
                        ];
                    }
                } else {
                    if ($okfile) {
                        $download_data[] = [
                            'filename'     => $dfilename,
                            'id'           => $download['did'],
                            'category'     => Language::aff_langue(stripslashes($download['dcategory'])),
                            'counter'      => Sanitize::wrh($download['dcounter']),
                        ];
                    }
                }
    
                if ($okfile) {
                    $lugar++;
                }
            }
        }

        return View::make('Modules/Download/Views/Partials/top_download_data', compact('download_data', 'ordre', 'form'));
    }

    // Front

    /**
     * Undocumented function
     *
     * @return void
     */
    public function tlist()
    {
        global $sortby, $dcategory;
    
        if ($dcategory == '') {
            $dcategory = addslashes(Config::get('npds.download_cat'));
        }
    
        $cate = stripslashes($dcategory);
    
        echo '
        <p class="lead">' . __d('download', 'Sélectionner une catégorie') . '</p>
        <div class="d-flex flex-column flex-sm-row flex-wrap justify-content-between my-3 border rounded">
            <p class="p-2 mb-0 ">';
    
        $acounter = sql_query("SELECT COUNT(*) FROM downloads");
        list($acount) = sql_fetch_row($acounter);
    
        if (($cate == __d('download', 'Tous')) or ($cate == ''))
            echo '<i class="fa fa-folder-open fa-2x text-muted align-middle me-2"></i><strong><span class="align-middle">' . __d('download', 'Tous') . '</span>
            <span class="badge bg-secondary ms-2 float-end my-2">' . $acount . '</span></strong>';
        else
            echo '<a href="download.php?dcategory=' . __d('download', 'Tous') . '&amp;sortby=' . $sortby . '"><i class="fa fa-folder fa-2x align-middle me-2"></i><span class="align-middle">' . __d('download', 'Tous') . '</span></a><span class="badge bg-secondary ms-2 float-end my-2">' . $acount . '</span>';
    
        $result = sql_query("SELECT DISTINCT dcategory, COUNT(dcategory) FROM downloads GROUP BY dcategory ORDER BY dcategory");
    
        echo '</p>';
    
        while (list($category, $dcount) = sql_fetch_row($result)) {
            $category = stripslashes($category);
            echo '<p class="p-2 mb-0">';
    
            if ($category == $cate)
                echo '<i class="fa fa-folder-open fa-2x text-muted align-middle me-2"></i><strong class="align-middle">' . aff_langue($category) . '<span class="badge bg-secondary ms-2 float-end my-2">' . $dcount . '</span></strong>';
            else {
                $category2 = urlencode($category);
                echo '<a href="download.php?dcategory=' . $category2 . '&amp;sortby=' . $sortby . '"><i class="fa fa-folder fa-2x align-middle me-2"></i><span class="align-middle">' . aff_langue($category) . '</span></a><span class="badge bg-secondary ms-2 my-2 float-end">' . $dcount . '</span>';
            }
            
            echo '</p>';
        }
    
        echo '</div>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $dcategory
     * @param [type] $sortby
     * @param [type] $fieldname
     * @param [type] $englishname
     * @return void
     */
    public function act_dl_tableheader($dcategory, $sortby, $fieldname, $englishname)
    {
        echo '
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '" title="' . __d('download', 'Croissant') . '" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-down"></i></a>&nbsp;
            ' . __d('download', $englishname) . '&nbsp;
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '&amp;sortorder=DESC" title="' . __d('download', 'Décroissant') . '" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-up"></i></a>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $dcategory
     * @param [type] $sortby
     * @param [type] $fieldname
     * @param [type] $englishname
     * @return void
     */
    public function inact_dl_tableheader($dcategory, $sortby, $fieldname, $englishname)
    {
        echo '
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '" title="' . __d('download', 'Croissant') . '" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-down" ></i></a>&nbsp;
            ' . __d('download', $englishname) . '&nbsp;
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '&amp;sortorder=DESC" title="' . __d('download', 'Décroissant') . '" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-up" ></i></a>';
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function dl_tableheader()
    {
        echo '</td>
        <td>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $did
     * @param [type] $ddescription
     * @param [type] $dcounter
     * @param [type] $dfilename
     * @param [type] $aff
     * @return void
     */
    public function popuploader($did, $ddescription, $dcounter, $dfilename, $aff)
    {
        global $dcategory, $sortby;
    
        $out_template = 0;
    
        if ($aff) {
            echo '
                <a class="me-3" href="#" data-bs-toggle="modal" data-bs-target="#mo' . $did . '" title="' . __d('download', 'Information sur le fichier') . '" data-bs-toggle="tooltip"><i class="fa fa-info-circle fa-2x"></i></a>
                <a href="download.php?op=mydown&amp;did=' . $did . '" target="_blank" title="' . __d('download', 'Charger maintenant') . '" data-bs-toggle="tooltip"><i class="fa fa-download fa-2x"></i></a>
                <div class="modal fade" id="mo' . $did . '" tabindex="-1" role="dialog" aria-labelledby="my' . $did . '" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title text-start" id="my' . $did . '">' . __d('download', 'Information sur le fichier') . ' - ' . $dfilename . '</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" title=""></button>
                        </div>
                        <div class="modal-body text-start">';
    
            geninfo($did, $out_template);
    
            echo '
                        </div>
                        <div class="modal-footer">
                            <a class="" href="download.php?op=mydown&amp;did=' . $did . '" title="' . __d('download', 'Charger maintenant') . '"><i class="fa fa-2x fa-download"></i></a>
                        </div>
                    </div>
                    </div>
                </div>';
        }
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $dcategory
     * @param [type] $sortby
     * @return void
     */
    public function SortLinks($dcategory, $sortby)
    {
        global $user;
    
        $dcategory = stripslashes($dcategory);
    
        echo '
            <thead>
                <tr>
                    <th class="text-center">' . __d('download', 'Fonctions') . '</th>
                    <th class="text-center n-t-col-xs-1" data-sortable="true" data-sorter="htmlSorter">' . __d('download', 'Type') . '</th>
                    <th class="text-center">';
    
        if ($sortby == 'dfilename' or !$sortby)
            act_dl_tableheader($dcategory, $sortby, "dfilename", "Nom");
        else
            inact_dl_tableheader($dcategory, $sortby, "dfilename", "Nom");
    
        echo '</th>
            <th class="text-center">';
    
        if ($sortby == "dfilesize")
            act_dl_tableheader($dcategory, $sortby, "dfilesize", "Taille");
        else
            inact_dl_tableheader($dcategory, $sortby, "dfilesize", "Taille");
    
        echo '</th>
            <th class="text-center">';
    
        if ($sortby == "dcategory")
            act_dl_tableheader($dcategory, $sortby, "dcategory", "Catégorie");
        else
            inact_dl_tableheader($dcategory, $sortby, "dcategory", "Catégorie");
    
        echo '</th>
            <th class="text-center">';
    
        if ($sortby == "ddate")
            act_dl_tableheader($dcategory, $sortby, "ddate", "Date");
        else
            inact_dl_tableheader($dcategory, $sortby, "ddate", "Date");
    
        echo '</th>
            <th class="text-center">';
    
        if ($sortby == "dver")
            act_dl_tableheader($dcategory, $sortby, "dver", "Version");
        else
            inact_dl_tableheader($dcategory, $sortby, "dver", "Version");
    
        echo '</th>
            <th class="text-center">';
    
        if ($sortby == "dcounter")
            act_dl_tableheader($dcategory, $sortby, "dcounter", "Compteur");
        else
            inact_dl_tableheader($dcategory, $sortby, "dcounter", "Compteur");
    
        echo '</th>';
    
        if ($user or autorisation(-127))
            echo '<th class="text-center n-t-col-xs-1"></th>';
    
        echo '
                </tr>
            </thead>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $dcategory
     * @param [type] $sortby
     * @param [type] $sortorder
     * @return void
     */
    public function listdownloads($dcategory, $sortby, $sortorder)
    {
        global $page, $user;
    
        if ($dcategory == '')
            $dcategory = addslashes(Config::get('npds.download_cat'));
    
        if (!$sortby)
            $sortby = 'dfilename';
    
        if (($sortorder != "ASC") && ($sortorder != "DESC"))
            $sortorder = "ASC";
    
        echo '<p class="lead">';
    
        echo __d('download', 'Affichage filtré pour') . "&nbsp;<i>";
    
        if ($dcategory == __d('download', 'Tous'))
            echo '<b>' . __d('download', 'Tous') . '</b>';
        else
            echo '<b>' . aff_langue(stripslashes($dcategory)) . '</b>';
    
        echo '</i>&nbsp;' . __d('download', 'trié par ordre') . '&nbsp;';
    
        // Shiney SQL Injection 11/2011
        $sortby2 = '';
        if ($sortby == 'dfilename')
            $sortby2 = __d('download', 'Nom') . "";
    
        if ($sortby == 'dfilesize')
            $sortby2 = __d('download', 'Taille du fichier') . "";
    
        if ($sortby == 'dcategory')
            $sortby2 = __d('download', 'Catégorie') . "";
    
        if ($sortby == 'ddate')
            $sortby2 = __d('download', 'Date de création') . "";
    
        if ($sortby == 'dver')
            $sortby2 = __d('download', 'Version') . "";
    
        if ($sortby == 'dcounter')
            $sortby2 = __d('download', 'Chargements') . "";
    
        // Shiney SQL Injection 11/2011
        if ($sortby2 == '')
            $sortby = 'dfilename';
    
        echo __d('download', 'de') . '&nbsp;<i><b>' . $sortby2 . '</b></i>
        </p>';
    
        echo '
        <table class="table table-hover mb-3 table-sm" id ="lst_downlo" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-show-columns="true"
        data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">';
    
        sortlinks($dcategory, $sortby);
    
        echo '
          <tbody>';
    
        if ($dcategory == __d('download', 'Tous'))
            $sql = "SELECT COUNT(*) FROM downloads";
        else
            $sql = "SELECT COUNT(*) FROM downloads WHERE dcategory='" . addslashes($dcategory) . "'";
    
        $result = sql_query($sql);
        list($total) =  sql_fetch_row($result);
    
        //
        if ($total > Config::get('npds.perpage')) {
            $pages = ceil($total / Config::get('npds.perpage'));
            if ($page > $pages) {
                $page = $pages;
            }
    
            if (!$page) {
                $page = 1;
            }
    
            $offset = ($page - 1) * Config::get('npds.perpage');
        } else {
            $offset = 0;
            $pages = 1;
            $page = 1;
        }
        //  
        $nbPages = ceil($total / Config::get('npds.perpage'));
        $current = 1;
    
        if ($page >= 1)
            $current = $page;
        else if ($page < 1)
            $current = 1;
        else
            $current = $nbPages;
    
        settype($offset, 'integer');
    
        if ($dcategory == __d('download', 'Tous'))
            $sql = "SELECT * FROM downloads ORDER BY $sortby $sortorder LIMIT $offset, Config::get('npds.perpage')";
        else
            $sql = "SELECT * FROM downloads WHERE dcategory='" . addslashes($dcategory) . "' ORDER BY $sortby $sortorder LIMIT $offset, Config::get('npds.perpage')";
    
        $result = sql_query($sql);
    
        while (list($did, $dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcat, $ddescription, $dperm) = sql_fetch_row($result)) {
    
            $Fichier = new File($durl); 
            $FichX = new FileManagement;
    
            $okfile = '';
            if (!stristr($dperm, ','))
                $okfile = autorisation($dperm);
            else {
                $ibidperm = explode(',', $dperm);
    
                foreach ($ibidperm as $v) {
                    if (autorisation($v) == true) {
                        $okfile = true;
                        break;
                    }
                }
            }
    
            echo '
            <tr>
                <td class="text-center">';
    
            if ($okfile == true)
                echo popuploader($did, $ddescription, $dcounter, $dfilename, true);
            else {
                echo popuploader($did, $ddescription, $dcounter, $dfilename, false);
    
                echo '<span class="text-danger"><i class="fa fa-ban fa-lg me-1"></i>' . __d('download', 'Privé') . '</span>';
            }
    
            echo '</td>
                <td class="text-center">' . $Fichier->Affiche_Extention('webfont') . '</td>
                <td>';
    
            if ($okfile == true)
                echo '<a href="download.php?op=mydown&amp;did=' . $did . '" target="_blank">' . $dfilename . '</a>';
            else
                echo '<span class="text-danger"><i class="fa fa-ban fa-lg me-1"></i>...</span>';
    
            echo '</td>
                <td class="small text-center">';
    
            if ($dfilesize != 0)
                echo $FichX->file_size_format($dfilesize, 1);
            else
                echo $FichX->file_size_auto($durl, 2);
    
            echo '</td>
                <td>' . aff_langue(stripslashes($dcat)) . '</td>
                <td class="small text-center">' . convertdate($ddate) . '</td>
                <td class="small text-center">' . $dver . '</td>
                <td class="small text-center">' . wrh($dcounter) . '</td>';
    
            if ($user != '' or autorisation(-127)) {
                echo '<td>';
    
                if (($okfile == true and $user != '') or autorisation(-127))
                    echo '<a href="download.php?op=broken&amp;did=' . $did . '" title="' . __d('download', 'Rapporter un lien rompu') . '" data-bs-toggle="tooltip"><i class="fas fa-lg fa-unlink"></i></a>';
    
                echo '</td>';
            }
    
            echo '</tr>';
        }
    
        echo '
            </tbody>
        </table>';
    
        $dcategory = StripSlashes($dcategory);
        echo '<div class="mt-3"></div>' . paginate_single('download.php?dcategory=' . $dcategory . '&amp;sortby=' . $sortby . '&amp;sortorder=' . $sortorder . '&amp;page=', '', $nbPages, $current, $adj = 3, '', $page);
    }

    // Admin

    /**
     * Undocumented function
     *
     * @param [type] $groupe
     * @return void
     */
    public function groupe($groupe)
    {
        $les_groupes = explode(',', $groupe);
        $mX = liste_group();

        $nbg = 0;
        $str = '';

        foreach ($mX as $groupe_id => $groupe_name) {
            $selectionne = 0;

            if ($les_groupes) {
                foreach ($les_groupes as $groupevalue) {
                    if (($groupe_id == $groupevalue) and ($groupe_id != 0)) $selectionne = 1;
                }
            }

            if ($selectionne == 1)
                $str .= '<option value="' . $groupe_id . '" selected="selected">' . $groupe_name . '</option>';
            else
                $str .= '<option value="' . $groupe_id . '">' . $groupe_name . '</option>';

            $nbg++;
        }

        if ($nbg > 5) 
            $nbg = 5;

        // si on veux traiter groupe multiple multiple="multiple"  et name="Mprivs"
        return ('
        <select multiple="multiple" class="form-select" id="mpri" name="Mprivs[]" size="' . $nbg . '">
        ' . $str . '
        </select>');
    }

    /**
     * Undocumented function
     *
     * @param [type] $member
     * @return void
     */
    public function droits($member)
    {
        echo '
        <div class="mb-3">
            <div class="form-check form-check-inline">';

        $checked = ($member == -127) ? ' checked="checked"' : '';

        echo '
                <input type="radio" id="adm" name="privs" class="form-check-input" value="-127" ' . $checked . ' />
                <label class="form-check-label" for="adm">' . __d('download', 'Administrateurs') . '</label>
            </div>
            <div class="form-check form-check-inline">';

        $checked = ($member == -1) ? ' checked="checked"' : '';

        echo '
                <input type="radio" id="ano" name="privs" class="form-check-input" value="-1" ' . $checked . ' />
                <label class="form-check-label" for="ano">' . __d('download', 'Anonymes') . '</label>
            </div>';

        echo '
        <div class="form-check form-check-inline">';

        if ($member > 0) {
            echo '
                    <input type="radio" id="mem" name="privs" value="1" class="form-check-input" checked="checked" />
                    <label class="form-check-label" for="mem">' . __d('download', 'Membres') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="tous" name="privs" class="form-check-input" value="0" />
                    <label class="form-check-label" for="tous">' . __d('download', 'Tous') . '</label>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="mpri">' . __d('download', 'Groupes') . '</label>
                <div class="col-sm-12">';

            echo groupe($member) . '
                </div>
            </div>';
        } else {
            $checked = ($member == 0) ? ' checked="checked"' : '';
            echo '
                    <input type="radio" id="mem" name="privs" class="form-check-input" value="1" />
                    <label class="form-check-label" for="mem">' . __d('download', 'Membres') . '</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="tous" name="privs" class="form-check-input" value="0"' . $checked . ' />
                    <label class="form-check-label" for="tous">' . __d('download', 'Tous') . '</label>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="mpri">' . __d('download', 'Groupes') . '</label>
                <div class="col-sm-12">';

            echo groupe($member) . '
                </div>
            </div>';
        }
    }

}
