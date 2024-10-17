<?php

namespace Modules\Wspad\Library;

use Npds\Config\Config;
use Modules\Npds\Support\Sanitize;
use Modules\Npds\Support\Facades\Crypt;
use Modules\Npds\Support\Facades\Language;
use Modules\Wspad\Contracts\WspadInterface;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Users\Support\Facades\UserPopover;


/**
 * Undocumented class
 */
class WspadManager implements WspadInterface 
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
     * Undocumented function
     *
     * @return void
     */
    public function Liste_Page()
    {
        global $auteur, $groupe, $couleur;
    
        // Paramètres utilisé par le script
        $ThisFile = "modules.php?ModPath=Wspad&amp;ModStart=$ModStart";

        echo '
        <script type="text/javascript">
        //<![CDATA[
        function confirm_deletedoc(page, gp) {
            var xhr_object = null;
            if (window.XMLHttpRequest) // FF
                xhr_object = new XMLHttpRequest();
            else if(window.ActiveXObject) // IE
                xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
            if (confirm("' . __d('wspad', 'Vous allez supprimer le document') . ' : "+page)) {
                xhr_object.open("GET", location.href="modules.php?ModPath=Wspad&ModStart=' . $ModStart . '&op=suppdoc&page="+page+"&member="+gp, false);
            }
        }
        //]]>
        </script>';
    
        $aff = '
        <h3 class="mb-3"><a class="arrow-toggle text-primary" id="show_cre_page" data-bs-toggle="collapse" data-bs-target="#cre_page" title="' . __d('wspad', 'Déplier la liste') . '"><i id="i_cre_page" class="toggle-icon fa fa-caret-down fa-lg" ></i></a>&nbsp;' . __d('wspad', 'Créer un document') . '</h3>
        <div id="cre_page" class="collapse" style ="padding-left:10px;">
            <form action="modules.php?ModPath=Wspad&amp;ModStart=' . $ModStart . '&amp;member=' . $groupe . '" method="post" name="wspadformfic">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="page">' . __d('wspad', 'Nom du document') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" id="page" name="page" maxlength="255" required="required" />
                    <span class="help-block small">' . __d('wspad', 'Caractères autorisés : a-z, A-Z, 0-9, -_.') . '</span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-8 ms-sm-auto">
                    <input class="btn btn-primary" type="submit" name="creer" value="' . __d('wspad', 'Créer') . '" />
                    <input type="hidden" name="op" value="creer" />
                    </div>
                </div>
            </form>
        </div>';
    
        echo $aff;
    
        $aff = '<h3 class="mb-3"><a class="arrow-toggle text-primary" id="show_paddoc" data-bs-toggle="collapse" data-bs-target="#lst_paddoc" title="' . __d('wspad', 'Déplier la liste') . '"><i id="i_lst_paddoc" class="toggle-icon fa fa-caret-down fa-lg" ></i></a>&nbsp;';
        
        $nb_pages = sql_num_rows(sql_query("SELECT COUNT(page) FROM wspad WHERE member='$groupe' GROUP BY page"));
        
        if ($groupe > 0) {
            $gp = sql_fetch_assoc(sql_query("SELECT groupe_name FROM groupes WHERE groupe_id='$groupe'"));
            $aff .= '<span class="badge bg-secondary me-2">' . $nb_pages . '</span>' . __d('wspad', 'Document(s) et révision(s) disponible(s) pour le groupe') . ' <span class="text-muted">' . Language::aff_langue($gp['groupe_name']) . " [$groupe]</span></h3>";
        } else
            $aff .= '<span class="badge bg-secondary me-2">' . $nb_pages . '</span>' . __d('wspad', 'Document(s) et révision(s) disponible(s) pour les administrateurs') . '</h3>';
        
        $aff .= '<div id="lst_paddoc" class="collapse" style =" padding-left:10px;">';
        
        if ($nb_pages > 0) {
            $ibid = 0;
            $pgibid = 0;
    
            $result = sql_query("SELECT DISTINCT page FROM wspad WHERE member='$groupe' ORDER BY page ASC");
    
            while (list($page) = sql_fetch_row($result)) {
    
                // Supression des verrous de mon groupe
                clearstatcache();
    
                $refresh = 15;
                $filename = "modules/Wspad/locks/$page-vgp-$groupe.txt";
    
                if (file_exists($filename)) {
                    if ((time() - $refresh) > filemtime($filename)) {
                        sql_query("UPDATE wspad SET verrou='' WHERE page='$page' AND member='$groupe'");
                        @unlink($filename);
                        $verrou = '';
                    }
                }
                // Supression des verrous de mon groupe
    
                $pgibid = $pgibid + 1;
    
                $aff .= '
                <div class="modal fade" id="renomeModal_' . $page . '" tabindex="-1" role="dialog" aria-labelledby="' . $page . '" aria-hidden="true">
                    <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">' . $page . '</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="renameForm" method="post" name="wspadformfic">
                                <div class="mb-3 row">
                                <label class="col-form-label col-12" for="newpage">Nouveau nom</label>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="newpage" name="newpage" />
                                    <span class="help-block" >' . __d('wspad', 'Caractères autorisés : a-z, A-Z, 0-9, -_.') . '</span>
                                </div>
                                </div>
                                <div class="mb-3 row">
                                <div class="col-sm-9 ms-sm-auto">
                                    <input type="hidden" name="page" value="' . $page . '" />
                                    <input type="hidden" name="op" value="renomer" />
                                    <button type="submit" class="btn btn-primary" name="creer">' . __d('wspad', 'Renommer') . '</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' . __d('wspad', 'Abandonner') . '</button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
                <hr />
                <h4><a class="arrow-toggle text-primary" id="show_lst_page_' . $pgibid . '" data-bs-toggle="collapse" data-bs-target="#lst_page_' . $pgibid . '" title="' . __d('wspad', 'Déplier la liste') . '"><i id="i_lst_page_' . $pgibid . '" class="fa fa-caret-down fa-lg" ></i></a>&nbsp;' . $page . '
                    <span class="float-end">
                    <a class="me-3" href="#" data-bs-toggle="modal" data-bs-target="#renomeModal_' . $page . '" ><i class="bi bi-pencil-square" title="' . __d('wspad', 'Renommer le document et toutes ses révisions') . '" data-bs-toggle="tooltip"></i></a>
                    <a class="text-danger" href="javascript:" onclick="confirm_deletedoc(\'' . $page . '\',\'' . $groupe . '\');" title="' . __d('wspad', 'Supprimer le document et toutes ses révisions') . '" data-bs-toggle="tooltip" data-bs-custom-class="n-danger-tooltip"><i class="bi bi-trash2-fill"></i></a>
                    </span>
                </h4>
                <div id="lst_page_' . $pgibid . '" class="collapse" style ="padding-left:10px;">';
                
                $result2 = sql_query("SELECT modtime, editedby, ranq, verrou FROM wspad WHERE page='$page' AND member='$groupe' ORDER BY ranq ASC");
    
                $aff .= '
                <table class=" table-sm" data-toggle="table" data-striped="true" data-mobile-responsive="true" >
                    <thead>
                    <tr>
                        <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">' . __d('wspad', 'Rev.') . '</th>
                        <th class="n-t-col-xs-4" data-sortable="true" data-halign="center">' . __d('wspad', 'Auteur') . '</th>
                        <th data-sortable="true" data-halign="center" data-align="right">' . __d('wspad', 'Date') . '</th>';
    
                $act = 0;
    
                while (list($modtime, $editedby, $ranq, $verrou) = sql_fetch_row($result2)) {
                    if ($act == 0) {
                        if (($auteur == $verrou) or ($verrou == '')) {
                            $aff .= '<th data-halign="center" data-align="right">' . __d('wspad', 'Actions') . '</th>';
    
                            //                  $divid=uniqid(mt_rand()); //usefull ???
                            $aff .= '
                            </tr>
                            </thead>
                            <tbody>';
                        } else {
                            $aff .= '
                                <th>' . __d('wspad', 'Actions') . '</th>
                            </tr>
                            </thead>
                            <tbody>';
                        }
    
                        $act = 1;
                    }
    
                    if ($ranq >= 100) {
                        $ibid = '';
                    } elseif ($ranq < 100 and $ranq >= 10) {
                        $ibid = '0';
                    } else {
                        $ibid = '00';
                    }
    
                    $aff .= '
                    <tr>
                        <td>' . $ibid . $ranq . '</td>
                        <td><div class="me-1" style="float: left; margin-top: 0.5rem; width: 1.5rem; height: 1.5rem; border-radius:50%; background-color: ' . $couleur[Sanitize::hexfromchr($editedby)] . ';"></div>' . UserPopover::userpopover($editedby, '40', 2) . '&nbsp;' . $editedby . '</td>
                        <td class="small">' . date(__d('wspad', 'dateinternal'), $modtime + ((int) Config::get('npds.gmt') * 3600)) . '</td>';
                    
                        // voir la révision du ranq x
                    $PopUp = JavaPopUp("modules.php?ModPath=Wspad&amp;ModStart=preview&amp;pad=" . Crypt::encrypt($page . "#wspad#" . $groupe . "#wspad#" . $ranq), "App_wspad", 500, 400);
                    
                    $aff .= '
                        <td>
                            <a class="me-2 fs-5" href="javascript:void(0);" onclick="window.open(' . $PopUp . ');" title="' . __d('wspad', 'Prévisualiser') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-eye"></i></a>';
                    
                    if (($auteur == $verrou) or ($verrou == '')) {
                        // recharger la révision du ranq x
                        $aff .= '
                        <a class="ms-2 fs-5" href="' . $ThisFile . '&amp;op=relo&amp;page=' . urlencode($page) . '&amp;member=' . $groupe . '&amp;ranq=' . $ranq . '" title="' . __d('wspad', 'Choisir') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-hand-index-thumb"></i></a>';
                        
                        // supprimer la révision du ranq x
                        $aff .= '
                        <a class="ms-2 fs-5 text-danger" href="' . $ThisFile . '&amp;op=supp&amp;page=' . urlencode($page) . '&amp;member=' . $groupe . '&amp;ranq=' . $ranq . '" title="' . __d('wspad', 'Supprimer la révision') . '" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="n-danger-tooltip"><i class="bi bi-trash2-fill"></i></a>';
                        
                        // exporter la révision du ranq x
                        $PopUp = JavaPopUp("modules.php?ModPath=Wspad&amp;ModStart=export&amp;type=doc&amp;pad=" . Crypt::encrypt($page . "#wspad#" . $groupe . "#wspad#" . $ranq), "App_wspad", 5, 5);
                        $aff .= '
                        <a class="ms-2 fs-5" href="javascript:void(0);" onclick="window.open(' . $PopUp . ');" title="' . __d('wspad', 'Exporter .doc') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-filetype-doc"></i></a>';
                        
                        // exporter en article 
                        $aff .= '
                        <a class="ms-2 fs-5" href="' . $ThisFile . '&amp;op=conv_new&amp;page=' . urlencode($page) . '&amp;member=' . $groupe . '&amp;ranq=' . $ranq . '" title="' . __d('wspad', 'Transformer en New') . '" data-bs-toggle="tooltip" data-bs-placement="left"><i class="bi bi-newspaper"></i></a>
                        </td>';
                    } else {
                        $aff .= '<i class="text-danger fs-5 me-2 bi bi-lock-fill"></i>' . __d('wspad', 'Verrouillé par : ') . UserPopover::userpopover($verrou, '40', 2) . '</td>';
                    }
                    
                    $aff .= '
                    </tr>';
                }
    
                $aff .= '
                    </tbody>
                </table>';
    
                $aff .= '
                </div>';
            }
        }
    
        echo $aff . '</div>';
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $page
     * @param [type] $ranq
     * @return void
     */
    public function Page($page, $ranq)
    {
        global $auteur, $groupe, $mess;
    
        $tmp = "
        <script type='text/javascript'>
            //<![CDATA[
                // timerID=10 secondes (verrou) : timerTTL=20 minutes (force la deconnexion)
                var timerID = null;
                var timerTTL = null;
                function TimerInit() {
                    timerID = setTimeout('TimerAct()',10000);
                    timerTTL= setTimeout('TimerDes()',1200000);
                }
                function TimerAct() {
                    clearTimeout(timerID);
                    ws_verrou('$auteur', '$page', '$groupe');
                    TimerInit();
                }
                function TimerDes() {
                    if (timerID != 0) {
                    bootbox.alert('" . __d('wspad', 'note : Enregistrer votre travail') . "', function() {});
                    }
                    clearTimeout(timerID);
                    timerID = 0;
                    clearTimeout(timerTTL);
                    timerTTL = 0;
                }
                function ws_verrou(xuser, xpage, xgroupe) {
                    var xmlhttp;
                    if (window.XMLHttpRequest) {
                    xmlhttp=new XMLHttpRequest();
                    } else {
                    xmlhttp=new ActiveXObject(\"Microsoft.XMLHTTP\");
                    }
                    var url='modules/Wspad/ws_verrou.php?verrou_user='+xuser+'&verrou_page='+xpage+'&verrou_groupe='+xgroupe+'&random='+Math.random();
                    xmlhttp.open('GET', url, true);
                    xmlhttp.send();
                    document.getElementById('verrous').src='modules/Wspad/images/ajax_waiting.gif';
                    document.getElementById('mess').innerHTML='';
                }
                document.getElementsByTagName('body')[0].setAttribute('onload','TimerInit();');
            //]]>
        </script>";
    
        // Analyse des verrous
        $filename = "modules/Wspad/locks/$page-vgp-$groupe.txt";
        $refresh = 15;
    
        clearstatcache();
    
        if (file_exists($filename)) {
            if (filemtime($filename) > (time() - $refresh)) {
    
                // propriétaire de ce verrou ?
                $cont = file($filename);
    
                if ($cont[0] == $auteur) {
                    $edition = true;
                    echo $tmp;
                } else {
                    $edition = false;
                }
    
            } else {
                // pose le verrou
                $fp = fopen($filename, "w");
                fwrite($fp, $auteur);
                fclose($fp);
    
                sql_query("UPDATE wspad SET verrou='' WHERE verrou='$auteur'");
                sql_query("UPDATE wspad SET verrou='$auteur' WHERE page='$page' AND member='$groupe'");
    
                $edition = true;
                echo $tmp;
            }
        } else {
            // pose le verrou
            $fp = fopen($filename, "w");
            fwrite($fp, $auteur);
            fclose($fp);
    
            sql_query("UPDATE wspad SET verrou='' WHERE verrou='$auteur'");
            sql_query("UPDATE wspad SET verrou='$auteur' WHERE page='$page' AND member='$groupe'");
    
            $edition = true;
            echo $tmp;
        }
        // Analyse des verrous
    
        $row = sql_fetch_assoc(sql_query("SELECT content, modtime, editedby, ranq FROM wspad WHERE page='$page' AND member='$groupe' AND ranq='$ranq'"));
        
        if (!$edition) {
            $mess = __d('wspad', 'Mode lecture seulement');
        }
    
        if (!is_array($row)) {
            $row['ranq'] = 1;
            $row['editedby'] = $auteur;
            $row['modtime'] = time();
            $row['content'] = '';
        } else {
            $row['ranq'] += 1;
        }

        // $surlignage = $couleur[Sanitize::hexfromchr($auteur)]; ???
    
        echo '
        <hr /><h3>' . __d('wspad', 'Document : ') . '</h3><h4>' . $page . '<span class="text-muted">&nbsp;[ ' . __d('wspad', 'révision') . ' : ' . $row['ranq'] . ' - ' . $row['editedby'] . ' / ' . date(__d('wspad', 'dateinternal'), $row['modtime'] + ((int) Config::get('npds.gmt') * 3600)) . ' ] </span> <span class="float-end"><img src="modules/Wspad/images/ajax_waiting.gif" id="verrous" title="wspad locks" /></span></h4>
        <div id="" class="alert alert-success" role="alert">
            <div id="mess">' . $mess . '</div>
        </div>wspad
        <form action="modules.php?ModPath=&amp;ModStart=' . $ModStart . '&amp;member=' . $groupe . '" method="post" name="wspadformcont">
            <div class="mb-3">
                <textarea class="tin form-control" rows="30" name="content" ><div class="mceEditable">' . $row['content'] . '</div></textarea>
            </div>';
    
        echo Editeur::aff_editeur('content', '');
    
        if ($edition) {
            echo '
            <div class="mb-3">
                <input class="btn btn-primary" type="submit" name="sauve" value="' . __d('wspad', 'Sauvegarder') . '" />
                <a class="btn btn-secondary" href="modules.php?ModPath=Wspad&amp;ModStart=' . $ModStart . '&amp;member=' . $groupe . '" >' . __d('wspad', 'Abandonner') . '</a>
                <input type="hidden" name="page" value="' . $page . '" />
                <input type="hidden" name="op" value="sauve" />
            </div>';
        }
        
        echo '</form>';
    }

}
