<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStories extends AdminController
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
    protected $hlpfile = 'newarticle';

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
    protected $f_meta_nom = 'adminStory';


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
        $this->f_titre = __d('news', 'Editer un Article');

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
     * @param [type] $sid
     * @return void
     */
    public function editStory($sid)
    {
        if (($sid == '') or ($sid == '0'))
            header("location: admin.php");
    
        $result = sql_query("SELECT catid, title, hometext, bodytext, topic, notes, ihome, date_finval,auto_epur FROM stories WHERE sid='$sid'");
        list($catid, $subject, $hometext, $bodytext, $topic, $notes, $ihome, $date_finval, $epur) = sql_fetch_row($result);
    
        $subject = stripslashes($subject);
        $hometext = stripslashes($hometext);
        $hometext = str_replace('<i class="fa fa-thumb-tack fa-2x me-2 text-muted"></i>', '', $hometext);
        $bodytext = stripslashes($bodytext);
        $notes = stripslashes($notes);
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topictext, topicname, topicimage, topicadmin FROM topics WHERE topicid='$topic'");
        list($topictext, $topicname, $topicimage, $topicadmin) = sql_fetch_row($result2);
    
        if ($radminsuper)
            $affiche = true;
        else {
            $topicadminX = explode(',', $topicadmin);
    
            for ($i = 0; $i < count($topicadminX); $i++) {
                if (trim($topicadminX[$i]) == $aid) 
                    $affiche = true;
            }
        }
    
        if (!$affiche) 
            header("location: admin.php");
    
        $topiclogo = '<span class="badge bg-secondary float-end"><strong>' . aff_langue($topicname) . '</strong></span>';

        $result = sql_query("SELECT topictext, topicimage FROM topics WHERE topicid='$topic'");
        list($topictext, $topicimage) = sql_fetch_row($result);
    
        echo '<hr />' . aff_local_langue('', 'local_user_language', '<label class="col-form-label">' . __d('news', 'Langue de Prévisualisation') . '</label>');
        
        if ($topicimage !== '') {
            if (!$imgtmp = theme_image('topics/' . $topicimage)) {
                $imgtmp = Config::get('npds.tipath') . $topicimage;
            }
    
            $timage = $imgtmp;
    
            if (file_exists($imgtmp))
                $topiclogo = '<img class="img-fluid " src="' . $timage . '" align="right" alt="" />';
        }
    
        global $local_user_language;
    
        echo '<div id="art_preview" class="card card-body mb-3">';
    
        echo code_aff('<h3>' . $subject . $topiclogo . '</h3>', '<div class="text-muted">' . $hometext . '</div>', $bodytext, $notes);
    
        echo '</div>';
    
        echo '
        <form id="editstory" action="admin.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="subject">' . __d('news', 'Titre') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="subject" name="subject" value="' . $subject . '" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_subject"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="topic">' . __d('news', 'Sujet') . '</label>
                <div class="col-sm-8">
                    <select class="form-select" id="topic" name="topic">';
    
        $toplist = sql_query("SELECT topicid, topictext, topicadmin FROM topics ORDER BY topictext");
    
        if ($radminsuper) 
            echo '<option value="">' . __d('news', 'Tous les Sujets') . '</option>';
    
        while (list($topicid, $topics, $topicadmin) = sql_fetch_row($toplist)) {
            $affiche = false;
    
            if ($radminsuper)
                $affiche = true;
            else {
                $topicadminX = explode(',', $topicadmin);
    
                for ($i = 0; $i < count($topicadminX); $i++) {
                    if (trim($topicadminX[$i]) == $aid) 
                        $affiche = true;
                }
            }
    
            if ($affiche) {
                $sel = $topicid == $topic ? 'selected="selected"' : '';
                echo '<option value="' . $topicid . '" ' . $sel . '>' . aff_langue($topics) . '</option>';
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
                <label class="col-form-label col-12" for="hometext">' . __d('news', 'Texte d\'introduction') . '</label>
                <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="hometext" name="hometext" >' . $hometext . '</textarea>
                </div>
            </div>';
    
        echo aff_editeur("hometext", "true");
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="bodytext">' . __d('news', 'Texte complet') . '</label>
                <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="bodytext" name="bodytext" >' . $bodytext . '</textarea>
                </div>
            </div>';
    
        echo aff_editeur("bodytext", "true");
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="notes">' . __d('news', 'Notes') . '</label>
                <div class="col-12">
                    <textarea class="tin form-control" rows="7" id="notes" name="notes" >' . $notes . '</textarea>
                </div>
            </div>';
    
        echo aff_editeur('notes', '');
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="Cdate">' . __d('news', 'Changer la date') . '?</label>
                <div class="col-sm-6 my-2">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="Cdate" name="Cdate" value="true" />
                    <label class="form-check-label" for="Cdate">' . __d('news', 'Oui') . '</label>
                    </div>
                    <span class="small help-block">' . __d('news', date("l")) . date(" " . __d('news', '"dateinternal'), time() + ((int) Config::get('npds.gmt') * 3600)) . '</span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-sm-6" for="Csid">' . __d('news', 'Remettre cet article en première position ? : ') . '</label>
                <div class="col-sm-6 my-2">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="Csid" name="Csid" value="true" />
                    <label class="form-check-label" for="Csid">' . __d('news', 'Oui') . '</label>
                    </div>
                </div>
            </div>';
    
        if ($date_finval != '') {
            $fd_pub = substr($date_finval, 0, 10);
            $fh_pub = substr($date_finval, 11, 5);
        } else {
            $fd_pub = (date("Y") + 99) . '-01-01';
            $fh_pub = '00:00';
        }
    
        publication(-1, $fd_pub, -1, $fh_pub, $epur);
    
        global $theme;
    
        echo '
            <input type="hidden" name="sid" value="' . $sid . '" />
            <input type="hidden" name="op" value="ChangeStory" />
            <input type="hidden" name="theme" value="' . $theme . '" />
            <div class="mb-3 row">
                <div class="col-12">
                    <input class="btn btn-primary" type="submit" value="' . __d('news', 'Modifier l\'Article') . '" />
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
        var formulid = ["editstory"];
        inpandfieldlen("subject",255);
        const choixgroupe = document.getElementById("choixgroupe");
        const mem_y = document.querySelector("#mem_y");
        const mem_n = document.querySelector("#mem_n");
        mem_y.checked ? "" : choixgroupe.style.display="none" ;
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $sid
     * @param [type] $subject
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $notes
     * @param [type] $catid
     * @param [type] $ihome
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $Cdate
     * @param [type] $Csid
     * @param [type] $date_finval
     * @param [type] $epur
     * @param [type] $theme
     * @param [type] $dd_pub
     * @param [type] $fd_pub
     * @param [type] $dh_pub
     * @param [type] $fh_pub
     * @return void
     */
    public function changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $Cdate, $Csid, $date_finval, $epur, $theme, $dd_pub, $fd_pub, $dh_pub, $fh_pub)
    {
        $date_finval = "$fd_pub $fh_pub:00";

        $subject = stripslashes(FixQuotes(str_replace('"', '&quot;', $subject)));
    
        $hometext = stripslashes(FixQuotes($hometext));
        $bodytext = stripslashes(FixQuotes($bodytext));
        $notes = stripslashes(FixQuotes($notes));
    
        if (($members == 1) and ($Mmembers == '')) 
            $ihome = '-127';
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) 
            $ihome = $Mmembers;
    
        if ($Cdate) {
            sql_query("UPDATE stories SET catid='$catid', title='$subject', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome',time=now(), date_finval='$date_finval', auto_epur='$epur', archive='0' WHERE sid='$sid'");
        } else {
            sql_query("UPDATE stories SET catid='$catid', title='$subject', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', date_finval='$date_finval', auto_epur='$epur' WHERE sid='$sid'");
        }
    
        if ($Csid) {
            sql_query("UPDATE stories SET hometext='<i class=\"fa fa-thumb-tack fa-2x me-2 text-muted\"></i> $hometext' WHERE sid='$sid'");
            list($Lsid) = sql_fetch_row(sql_query("SELECT sid FROM stories ORDER BY sid DESC"));
    
            $Lsid++;
    
            sql_query("UPDATE stories SET sid='$Lsid' WHERE sid='$sid'");
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("UPDATE posts SET topic_id='$Lsid' WHERE forum_id='$forum' AND topic_id='$topic'");
            }
    
            $sid = $Lsid;
        }
    
        global $aid;
        Ecr_Log('security', "changeStory($sid, $subject, hometext..., bodytext..., $topic, notes..., $catid, $ihome, $members, $Mmembers, $Cdate, $Csid, $date_finval,$epur,$theme) by AID : $aid", '');
        
        if (Config::get('npds.ultramode')) {
            ultramode();
        }
    
        // Cluster Paradise
        if (file_exists("modules/cluster-paradise/cluster-activate.php")) {
            include("modules/cluster-paradise/cluster-activate.php");
        }
    
        if (file_exists("modules/cluster-paradise/cluster-M.php")) {
            include("modules/cluster-paradise/cluster-M.php");
        }
        // Cluster Paradise
    
        // Réseaux sociaux
        if (file_exists('modules/App_twi/App_to_twi.php')) {
            include('modules/App_twi/App_to_twi.php');
        }
    
        if (file_exists('modules/App_fbk/App_to_fbk.php')) {
            include('modules/App_twi/App_to_fbk.php');
        }
        // Réseaux sociaux
    
        redirect_url("admin.php?op=EditStory&sid=$sid");
    }

}
