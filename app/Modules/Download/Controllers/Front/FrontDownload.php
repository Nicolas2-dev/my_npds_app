<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontDownload extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        // settype($op, 'string');

        // switch ($op) {
        //     case 'main':
        //         main();
        //         break;
        
        //     case 'mydown':
        //         transferfile($did);
        //         break;
        
        //     case 'geninfo':
        //         geninfo($did, $out_template);
        //         break;
        
        //     case 'broken':
        //         broken($did);
        //         break;
        
        //     default:
        //         main();
        //         break;
        // }
        
    }

    function geninfo($did, $out_template)
    {
        
    
        settype($did, 'integer');
        settype($out_template, 'integer');
    
        $result = sql_query("SELECT dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM downloads WHERE did='$did'");
        list($dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $dperm) = sql_fetch_row($result);
    
        $okfile = false;
        if (!stristr($dperm, ','))
            $okfile = autorisation($dperm);
        else {
            $ibidperm = explode(',', $dperm);
    
            foreach ($ibidperm as $v) {
                if (autorisation($v)) {
                    $okfile = true;
                    break;
                }
            }
        }
    
        if ($okfile) {
            $title = $dfilename;
    
            if ($out_template == 1) {
                include('header.php');
    
                echo '
                <h2 class="mb-3">' . __d('download', 'Chargement de fichiers') . '</h2>
                <div class="card">
                    <div class="card-header"><h4>' . $dfilename . '<span class="ms-3 text-muted small">@' . $durl . '</h4></div>
                    <div class="card-body">';
            }
    
            echo '<p><strong>' . __d('download', 'Taille du fichier') . ' : </strong>';
    
            $Fichier = new File($durl);
            $objZF = new FileManagement;
    
            if ($dfilesize != 0)
                echo $objZF->file_size_format($dfilesize, 1);
            else
                echo $objZF->file_size_auto($durl, 2);
    
            echo '</p>
                    <p><strong>' . __d('download', 'Version') . '&nbsp;:</strong>&nbsp;' . $dver . '</p>
                    <p><strong>' . __d('download', 'Date de chargement sur le serveur') . '&nbsp;:</strong>&nbsp;' . convertdate($ddate) . '</p>
                    <p><strong>' . __d('download', 'Chargements') . '&nbsp;:</strong>&nbsp;' . wrh($dcounter) . '</p>
                    <p><strong>' . __d('download', 'Catégorie') . '&nbsp;:</strong>&nbsp;' . aff_langue(stripslashes($dcategory)) . '</p>
                    <p><strong>' . __d('download', 'Description') . '&nbsp;:</strong>&nbsp;' . aff_langue(stripslashes($ddescription)) . '</p>
                    <p><strong>' . __d('download', 'Auteur') . '&nbsp;:</strong>&nbsp;' . $duser . '</p>
                    <p><strong>' . __d('download', 'Page d\'accueil') . '&nbsp;:</strong>&nbsp;<a href="http://' . $dweb . '" target="_blank">' . $dweb . '</a></p>';
    
            if ($out_template == 1) {
                echo '
                    <a class="btn btn-primary" href="download.php?op=mydown&amp;did=' . $did . '" target="_blank" title="' . __d('download', 'Charger maintenant') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-lg fa-download"></i></a>
                    </div>
                </div>';
    
                include('footer.php');
            }
        } else
            Header("Location: download.php");
    }
    
    function tlist()
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
    
    function act_dl_tableheader($dcategory, $sortby, $fieldname, $englishname)
    {
        echo '
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '" title="' . __d('download', 'Croissant') . '" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-down"></i></a>&nbsp;
            ' . __d('download', $englishname) . '&nbsp;
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '&amp;sortorder=DESC" title="' . __d('download', 'Décroissant') . '" data-bs-toggle="tooltip" ><i class="fa fa-sort-amount-up"></i></a>';
    }
    
    function inact_dl_tableheader($dcategory, $sortby, $fieldname, $englishname)
    {
        echo '
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '" title="' . __d('download', 'Croissant') . '" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-down" ></i></a>&nbsp;
            ' . __d('download', $englishname) . '&nbsp;
            <a class="d-none d-sm-inline" href="download.php?dcategory=' . $dcategory . '&amp;sortby=' . $fieldname . '&amp;sortorder=DESC" title="' . __d('download', 'Décroissant') . '" data-bs-toggle="tooltip"><i class="fa fa-sort-amount-up" ></i></a>';
    }
    
    function dl_tableheader()
    {
        echo '</td>
        <td>';
    }
    
    function popuploader($did, $ddescription, $dcounter, $dfilename, $aff)
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
    
    function SortLinks($dcategory, $sortby)
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
    
    function listdownloads($dcategory, $sortby, $sortorder)
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
    
    function main()
    {
        global $dcategory, $sortby, $sortorder;
    
        $dcategory  = removeHack(stripslashes(htmlspecialchars(urldecode($dcategory ?: ''), ENT_QUOTES, cur_charset))); // electrobug
        $dcategory = str_replace("&#039;", "\'", $dcategory);
        $sortby  = removeHack(stripslashes(htmlspecialchars(urldecode($sortby ?: ''), ENT_QUOTES, cur_charset))); // electrobug
    
        include("header.php");
    
        echo '
        <h2>' . __d('download', 'Chargement de fichiers') . '</h2>
        <hr />';
    
        tlist();
    
        if ($dcategory != __d('download', 'Aucune catégorie'))
            listdownloads($dcategory, $sortby, $sortorder);
    
        if (file_exists("static/download.ban.txt"))
            include("static/download.ban.txt");
    
        include("footer.php");
    }
    
    function transferfile($did)
    {
        
    
        settype($did, 'integer');
    
        $result = sql_query("SELECT dcounter, durl, perms FROM downloads WHERE did='$did'");
        list($dcounter, $durl, $dperm) = sql_fetch_row($result);
    
        if (!$durl) {
            include("header.php");
    
            echo '
            <h2>' . __d('download', 'Chargement de fichiers') . '</h2>
            <hr />
            <div class="lead alert alert-danger">' . __d('download', 'Ce fichier n\'existe pas ...') . '</div>';
    
            include("footer.php");
        } else {
            if (stristr($dperm, ',')) {
                $ibid = explode(',', $dperm);
    
                foreach ($ibid as $v) {
                    $aut = true;
    
                    if (autorisation($v) == true) {
                        $dcounter++;
                        sql_query("UPDATE downloads SET dcounter='$dcounter' WHERE did='$did'");
    
                        header("location: " . str_replace(basename($durl), rawurlencode(basename($durl)), $durl));
                        break;
                    } else
                        $aut = false;
                }
    
                if ($aut == false)
                    Header("Location: download.php");
            } else {
                if (autorisation($dperm)) {
                    $dcounter++;
    
                    sql_query("UPDATE downloads SET dcounter='$dcounter' WHERE did='$did'");
                    header("location: " . str_replace(basename($durl), rawurlencode(basename($durl)), $durl));
                } else
                    Header("Location: download.php");
            }
        }
    }
    
    function broken($did)
    {
        global $user, $cookie;
    
        settype($did, 'integer');
    
        if ($user) {
            if ($did) {

                settype($did, "integer");
    
                $message = Config::get('npds.nuke_url') . "\n" . __d('download', 'Téléchargements') . " ID : $did\n" . __d('download', 'Auteur') . " $cookie[1] / IP : " . getip() . "\n\n";
    
                include 'signat.php';
    
                send_email(Config::get('npds.notify_email'), html_entity_decode(__d('download', 'Rapporter un lien rompu'), ENT_COMPAT | ENT_HTML401, cur_charset), nl2br($message), Config::get('npds.notify_from'), false, "html", '');
    
                include("header.php");
    
                echo '
                <div class="alert alert-success">
                <p class="lead">' . __d('download', 'Pour des raisons de sécurité, votre nom d\'utilisateur et votre adresse IP vont être momentanément conservés.') . '<br />' . __d('download', 'Merci pour cette information. Nous allons l\'examiner dès que possible.') . '</p>
                </div>';
    
                include("footer.php");
            } else
                Header("Location: download.php");
        } else
            Header("Location: download.php");
    }

}