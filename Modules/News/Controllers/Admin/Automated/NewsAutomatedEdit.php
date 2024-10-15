<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsAutomatedEdit extends AdminController
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
    protected $hlpfile = 'automated';

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
    protected $f_meta_nom = 'autoStory';


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
        $this->f_titre = __d('news', 'Editer un Article');;

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
     * @param [type] $anid
     * @return void
     */
    public function autoEdit($anid)
    {
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
    
    /**
     * Undocumented function
     *
     * @param [type] $anid
     * @param [type] $title
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $notes
     * @param [type] $catid
     * @param [type] $ihome
     * @param [type] $informant
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $date_debval
     * @param [type] $date_finval
     * @param [type] $epur
     * @return void
     */
    public function autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval, $date_finval, $epur)
    {
        $date_debval = !isset($date_debval) ? $dd_pub . ' ' . $dh_pub . ':01' : $date_debval;
        $date_finval = !isset($date_finval) ? $fd_pub . ' ' . $fh_pub . ':01' : $date_finval;

        if ($date_finval < $date_debval)
            $date_finval = $date_debval;

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
