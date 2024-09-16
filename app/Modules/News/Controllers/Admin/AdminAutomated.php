<?php

namespace App\Modules\News\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminAutomated extends AdminController
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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'autoStory';
        // $f_titre = __d('news', 'Articles programmés');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // include("publication.php");
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/automated.html";

        // switch ($op) {
        //     case 'autoStory':
        //         autoStory();
        //         break;
        
        //     case 'autoDelete':
        //         autodelete($anid);
        //         break;
        
        //     case 'autoEdit':
        //         autoEdit($anid);
        //         break;
        
        //     case 'autoSaveEdit':
        //         $date_debval = !isset($date_debval) ? $dd_pub . ' ' . $dh_pub . ':01' : $date_debval;
        //         $date_finval = !isset($date_finval) ? $fd_pub . ' ' . $fh_pub . ':01' : $date_finval;
        
        //         if ($date_finval < $date_debval)
        //             $date_finval = $date_debval;
                
        //         autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval, $date_finval, $epur);
        //         break;
        // }        
    // }

    function puthome($ihome)
    {
        echo '
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="ihome">' . __d('news', 'Publier dans la racine ?') . '</label>';
    
        $sel1 = 'checked="checked"';
        $sel2 = '';
    
        if ($ihome == 1) {
            $sel1 = '';
            $sel2 = 'checked="checked"';
        }
    
        echo '
                <div class="col-sm-8 my-2">
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="ihome" name="ihome" value="0" ' . $sel1 . ' />
                    <label class="form-check-label" for="ihome">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="ihome1" name="ihome" value="1" ' . $sel2 . ' />
                    <label class="form-check-label" for="ihome1">' . __d('news', 'Non') . '</label>
                    </div>
                    <p class="help-block">' . __d('news', 'Ne s\'applique que si la catégorie : \'Articles\' n\'est pas sélectionnée.') . '</p>
                </div>
            </div>';
    
        $sel1 = '';
        $sel2 = 'checked="checked"';
    
        echo '
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="members">' . __d('news', 'Seulement aux membres') . ', ' . __d('news', 'Groupe') . '.</label>
                <div class="col-sm-8 my-2">
                    <div class="form-check form-check-inline">';
    
        if ($ihome < 0) {
            $sel1 = 'checked="checked"';
            $sel2 = '';
        }
    
        if (($ihome > 1) and ($ihome <= 127)) {
            $Mmembers = $ihome;
            $sel1 = 'checked="checked"';
            $sel2 = '';
        }
        
        echo '
                    <input class="form-check-input" type="radio" id="members" name="members" value="1" ' . $sel1 . ' />
                    <label class="form-check-label" for="members">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="members1" name="members" value="0" ' . $sel2 . ' />
                    <label class="form-check-label" for="members1">' . __d('news', 'Non') . '</label>
                    </div>
                </div>
            </div>';
    
        // ---- Groupes
        $mX = liste_group();
        $tmp_groupe = '';
    
        isset($Mmember) ? $Mmembers : $Mmembers = '';
    
        foreach ($mX as $groupe_id => $groupe_name) {
            if ($groupe_id == '0') 
                $groupe_id = '';
    
            $sel3 = $Mmembers == $groupe_id ? 'selected="selected"' : '';
    
            $tmp_groupe .= '<option value="' . $groupe_id . '" ' . $sel3 . '>' . $groupe_name . '</option>';
        }
    
        echo '
            <div class="mb-3 row" id="choixgroupe">
                <label class="col-sm-4 col-form-label" for="Mmembers">' . __d('news', 'Groupe') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="Mmembers" name="Mmembers">' . $tmp_groupe . '</select>
                </div>
            </div>';
    }
    
    function SelectCategory($cat)
    {
        $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
        echo ' 
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="catid">' . __d('news', 'Catégorie') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="catid" name="catid">';
    
        if ($cat == 0) 
            $sel = 'selected="selected"';
        else 
            $sel = '';
    
        echo 'option name="catid" value="0" ' . $sel . '>' . __d('news', 'Articles') . '</option>';
    
        while (list($catidX, $title) = sql_fetch_row($selcat)) {
            if ($catidX == $cat) 
                $sel = 'selected';
            else 
                $sel = '';
    
            echo '<option name="catid" value="' . $catidX . '" ' . $sel . '>' . aff_langue($title) . '</option>';
        }
    
        echo '
                    </select>
                    <p class="help-block text-end"><a href="admin.php?op=AddCategory" class="btn btn-outline-primary btn-sm" title="' . __d('news', 'Ajouter') . '" data-bs-toggle="tooltip" ><i class="fa fa-plus-square fa-lg"></i></a>&nbsp;<a class="btn btn-outline-primary btn-sm" href="admin.php?op=EditCategory" title="' . __d('news', 'Editer') . '" data-bs-toggle="tooltip" ><i class="fa fa-edit fa-lg"></i></a>&nbsp;<a class="btn btn-outline-danger btn-sm" href="admin.php?op=DelCategory" title="' . __d('news', 'Effacer') . '" data-bs-toggle="tooltip"><i class="fas fa-trash fa-lg"></i></a></p>
                </div>
            </div>';
    }
    
    function autoStory()
    {
        echo '
        <hr />
        <h3>' . __d('news', 'Liste des articles') . '</h3>
        <table id="tab_adm" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th class="n-t-col-xs-6" data-sortable="true" data-halign="center">' . __d('news', 'Titre') . '</th>
                    <th class="n-t-col-xs-4 small" data-sortable="true" data-align="center" data-align="right">' . __d('news', 'Date prévue de publication') . '</th>
                    <th class="n-t-col-xs-2" data-align="center">' . __d('news', 'Fonctions') . '</th>
                </tr>
            </thead>
            <tbody>';
    
        $result = sql_query("SELECT anid, title, date_debval, topic FROM autonews ORDER BY date_debval ASC");
    
        while (list($anid, $title, $time, $topic) = sql_fetch_row($result)) {
            if ($anid != '') {
                $affiche = false;
    
                $result2 = sql_query("SELECT topicadmin, topicname FROM topics WHERE topicid='$topic'");
                list($topicadmin, $topicname) = sql_fetch_row($result2);
    
                if ($radminsuper) {
                    $affiche = true;
                } else {
                    $topicadminX = explode(",", $topicadmin);
    
                    for ($i = 0; $i < count($topicadminX); $i++) {
                        if (trim($topicadminX[$i]) == $aid) $affiche = true;
                    }
                }
    
                if ($title == '') {
                    $title = __d('news', 'Aucun Sujet');
                }
    
                if ($affiche) {
                    echo '
                    <tr>
                        <td><a href="admin.php?op=autoEdit&amp;anid=' . $anid . '">' . aff_langue($title) . '</a></td>
                        <td>' . formatTimestamp("nogmt" . $time) . '</td>
                        <td><a href="admin.php?op=autoEdit&amp;anid=' . $anid . '"><i class="fa fa-edit fa-lg me-2" title="' . __d('news', 'Afficher l\'article') . '" data-bs-toggle="tooltip"></i></a><a href="admin.php?op=autoDelete&amp;anid=' . $anid . '">&nbsp;<i class="fas fa-trash fa-lg text-danger" title="' . __d('news', 'Effacer l\'Article') . '" data-bs-toggle="tooltip" ></i></a></td>
                    </tr>';
                } else {
                    echo '
                    <tr>
                        <td><i>' . aff_langue($title) . '</i></td>
                        <td>' . formatTimestamp("nogmt" . $time) . '</td>
                        <td>&nbsp;</td>
                    </tr>';
                }
            }
        }
    
        echo '
            </tbody>
        </table>';
    
        adminfoot('', '', '', '');
    }
    
    function autoDelete($anid)
    {
        sql_query("DELETE FROM autonews WHERE anid='$anid'");
    
        Header("Location: admin.php?op=autoStory");
    }
    
    function autoEdit($anid)
    {
        global $aid, $hlpfile, $radminsuper;
    
        $f_meta_nom = 'autoStory';
        $f_titre = __d('news', 'Editer un Article');

        $result = sql_query("SELECT catid, title, time, hometext, bodytext, topic, informant, notes, ihome, date_debval,date_finval,auto_epur FROM autonews WHERE anid='$anid'");
        
        list($catid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome, $date_debval, $date_finval, $epur) = sql_fetch_row($result);
        sql_free_result($result);
        
        $titre = stripslashes($title);
        $hometext = stripslashes($hometext);
        $bodytext = stripslashes($bodytext);
        $notes = stripslashes($notes);
    
        if ($topic < 1) {
            $topic = 1;
        }
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topicname, topictext, topicimage, topicadmin FROM topics WHERE topicid='$topic'");
        list($topicname, $topictext, $topicimage, $topicadmin) = sql_fetch_row($result2);
    
        if ($radminsuper)
            $affiche = true;
        else {
            $topicadminX = explode(',', $topicadmin);
    
            for ($i = 0; $i < count($topicadminX); $i++) {
                if (trim($topicadminX[$i]) == $aid) $affiche = true;
            }
        }
    
        if (!$affiche)
            header("location: admin.php?op=autoStory");
    
        $topiclogo = '<span class="badge bg-secondary" title="' . $topictext . '" data-bs-toggle="tooltip" data-bs-placement="left"><strong>' . aff_langue($topicname) . '</strong></span>';

        echo '
        <hr />
        <h3>' . __d('news', 'Editer l\'Article Automatique') . '</h3>
        ' . aff_local_langue('', 'local_user_language', __d('news', 'Langue de Prévisualisation')) . '
        <div class="card card-body mb-3">';
    
        if ($topicimage !== '') {
            if (!$imgtmp = theme_image('topics/' . $topicimage)) 
                $imgtmp = Config::get('npds.tipath') . $topicimage;
    
            $timage = $imgtmp;
    
            if (file_exists($imgtmp))
                $topiclogo = '<img class="img-fluid " src="' . $timage . '" align="right" alt="topic_logo" loading="lazy" title="' . $topictext . '" data-bs-toggle="tooltip" data-bs-placement="left" />';
        }
    
        code_aff('<div class="d-flex"><div class="w-100 p-2 ps-0"><h3>' . $titre . '</h3></div><div class="align-self-center p-2 flex-shrink-1 h3">' . $topiclogo . '</div></div>', '<div class="text-muted">' . $hometext . '</div>', $bodytext, $notes);
    
        echo '<hr /><b>' . __d('news', 'Utilisateur') . '</b>' . $informant . '<br />';
    
        echo '
        </div>
        <form action="admin.php" method="post" name="adminForm" id="autoedit">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="title">' . __d('news', 'Titre') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="title" name="title" size="50" value="' . $titre . '" required="required" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="topic">' . __d('news', 'Sujet') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="topic" name="topic">';
    
        $toplist = sql_query("SELECT topicid, topictext, topicadmin FROM topics ORDER BY topictext");
    
        if ($radminsuper) 
            echo '<option value="">' . __d('news', 'Tous les Sujets') . '</option>';
    
        while (list($topicid, $topics, $topicadmin) = sql_fetch_row($toplist)) {
            $affiche = false;
    
            if ($radminsuper) {
                $affiche = true;
            } else {
                $topicadminX = explode(',', $topicadmin);
    
                for ($i = 0; $i < count($topicadminX); $i++) {
                    if (trim($topicadminX[$i]) == $aid) $affiche = true;
                }
            }
    
            if ($affiche) {
                $sel = $topicid == $topic ? 'selected="selected" ' : '';
                echo '<option ' . $sel . ' value="' . $topicid . '">' . aff_langue($topics) . '</option>';
            }
        }
    
        echo ' 
                    </select>
                </div>
            </div>';
    
        SelectCategory($catid);
        puthome($ihome);
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="hometext">' . __d('news', 'Texte d\'introduction') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" rows="25" id="hometext" name="hometext" >' . $hometext . '</textarea>
                </div>
            </div>
            ' . aff_editeur('hometext', '') . '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="bodytext">' . __d('news', 'Texte étendu') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" rows="25" id="bodytext" name="bodytext" >' . $bodytext . '</textarea>
                </div>
            </div>
            ' . aff_editeur('bodytext', '');
    
        if ($aid != $informant) {
            echo '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="notes">' . __d('news', 'Notes') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" rows="7" id="notes" name="notes">' . $notes . '</textarea>
                </div>
            </div>
            ' . aff_editeur('notes', '');
        }
    
        $dd_pub = substr($date_debval, 0, 10);
        $fd_pub = substr($date_finval, 0, 10);
        $dh_pub = substr($date_debval, 11, 5);
        $fh_pub = substr($date_finval, 11, 5);
    
        publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
    
        echo '
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input type="hidden" name="anid" value="' . $anid . '" />
                    <input type="hidden" name="informant" value="' . $informant . '" />
                    <input type="hidden" name="op" value="autoSaveEdit" />
                    <input class="btn btn-primary" type="submit" value="' . __d('news', 'Sauver les modifications') . '" />
                </div>
            </div>
        </form>';
    
        $fv_parametres = '
        !###!
        mem_y.addEventListener("change", function (e) {
            if(e.target.checked) {
                choixgroupe.style.display="flex";
            }
        });
        mem_n.addEventListener("change", function (e) {
            if(e.target.checked) {
                choixgroupe.style.display="none";
            }
        });
        ';
    
        $arg1 = '
            var formulid = ["autoedit"];
            const choixgroupe = document.getElementById("choixgroupe");
            const mem_y = document.querySelector("#members");
            const mem_n = document.querySelector("#members1");
            mem_y.checked ? "" : choixgroupe.style.display="none" ;
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    function autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval, $date_finval, $epur)
    {
        global $aid;
    
        $title = stripslashes(FixQuotes(str_replace('"', '&quot;', $title)));
        $hometext = stripslashes(FixQuotes($hometext));
        $bodytext = stripslashes(FixQuotes($bodytext));
        $notes = stripslashes(FixQuotes($notes));
    
        if (($members == 1) and ($Mmembers == '')) 
            $ihome = '-127';
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) 
            $ihome = $Mmembers;
    
        $result = sql_query("UPDATE autonews SET catid='$catid', title='$title', time=now(), hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', date_debval='$date_debval', date_finval='$date_finval', auto_epur='$epur' WHERE anid='$anid'");
        
        if (Config::get('npds.ultramode'))
            ultramode();
    
        Header("Location: admin.php?op=autoEdit&anid=$anid");
    }

}
