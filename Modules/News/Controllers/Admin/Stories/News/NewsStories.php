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
    protected $f_meta_nom = 'admiadminStorynStory';


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
        $this->f_titre = __d('news', 'Nouvel Article');

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
    public function adminStory()
    {
        echo '
        <hr />
        <form id="storiesnewart" action="admin.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="subject">' . __d('news', 'Titre') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" name="subject" id="subject" value="" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_subject"></span>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="topic">' . __d('news', 'Sujet') . '</label>
                <div class="col-sm-8">
                <select class="form-select" id="topic" name="topic">';
    
        $toplist = sql_query("SELECT topicid, topictext, topicadmin FROM topics ORDER BY topictext");
    
        //probablement ici aussi mettre les droits pour les gestionnaires de topics ??
        if ($radminsuper) 
            echo '<option value="">' . __d('news', 'Sélectionner un Sujet') . '</option>';
    
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
                if ($topicid == $topic) 
                    $sel = 'selected="selected"';
    
                echo '<option ' . $sel . ' value="' . $topicid . '">' . aff_langue($topics) . '</option>';
                $sel = '';
            }
        }
    
        echo '
                    </select>
                </div>
            </div>';
    
        $cat = 0;
    
        SelectCategory($cat);
        puthome($ihome);
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="hometext">' . __d('news', 'Texte d\'introduction') . '</label>
                <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="hometext" name="hometext">' . $hometext . '</textarea>
                </div>
            </div>';
    
        echo aff_editeur('hometext', '');
    
        echo '
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="bodytext">' . __d('news', 'Texte étendu') . '</label>
                <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="bodytext" name="bodytext" >' . $bodytext . '</textarea>
                </div>
            </div>';
    
        echo aff_editeur('bodytext', '');
    
        publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
    
        echo '
            <input type="hidden" name="author" value="' . $aid . '" />
            <input type="hidden" name="op" value="PreviewAdminStory" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input class="btn btn-primary" type="submit" name="preview" value="' . __d('news', 'Prévisualiser') . '" />
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
        var formulid = ["storiesnewart"];
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
     * @param [type] $subject
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $catid
     * @param [type] $ihome
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $dd_pub
     * @param [type] $fd_pub
     * @param [type] $dh_pub
     * @param [type] $fh_pub
     * @param [type] $epur
     * @return void
     */
    public function previewAdminStory($subject, $hometext, $bodytext, $topic, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
    {
        $subject = stripslashes(str_replace('"', '&quot;', $subject));
        $hometext = stripslashes(dataimagetofileurl($hometext, 'cache/ai'));
        $bodytext = stripslashes(dataimagetofileurl($bodytext, 'cache/ac'));
    
        settype($sel, 'string');
    
        if ($topic < 1) 
            $topic = 1;
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topictext, topicimage, topicadmin FROM topics WHERE topicid='$topic'");
        list($topictext, $topicimage, $topicadmin) = sql_fetch_row($result2);
    
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
    
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Nouvel Article');
    
        $topiclogo = '<span class="badge bg-secondary float-end"><strong>' . aff_langue($topictext) . '</strong></span>';

        global $local_user_language;

        echo '
        <hr />
        <h3>' . __d('news', 'Prévisualiser l\'Article') . '</h3>
        <form id="storiespreviswart" action="admin.php" method="post" name="adminForm">
            <label class="col-form-label">' . __d('news', 'Langue de Prévisualisation') . '</label> 
            ' . aff_localzone_langue("local_user_language") . '
            <div class="card card-body mb-3">';
    
        if ($topicimage !== '') {
            if (!$imgtmp = theme_image('topics/' . $topicimage)) {
                $imgtmp = Config::get('npds.tipath') . $topicimage;
            }
    
            $timage = $imgtmp;
    
            if (file_exists($imgtmp))
                $topiclogo = '<img class="img-fluid " src="' . $timage . '" align="right" alt="" />';
        }
    
        code_aff('<h3>' . $subject . $topiclogo . '</h3>', '<div class="text-muted">' . $hometext . '</div>', $bodytext, '');
    
        echo '
            </div>
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label" for="subject">' . __d('news', 'Titre') . '</label>
                    <div class="col-sm-8">
                    <input class="form-control" type="text" name="subject" id="subject" value="' . $subject . '" maxlength="255" required="required" />
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
                if ($topicid == $topic) {
                    $sel = 'selected="selected"';
                }
    
                echo '<option ' . $sel . ' value="' . $topicid . '">' . aff_langue($topics) . '</option>';
    
                $sel = '';
            }
        }
    
        echo '
                    </select>
                    </div>
                </div>';
    
        $cat = $catid;
    
        SelectCategory($catid);
    
        if (($members == 1) and ($Mmembers == '')) 
            $ihome = '-127';
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) 
            $ihome = $Mmembers;
    
        puthome($ihome);
    
        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-12" for="hometext">' . __d('news', 'Texte d\'introduction') . '</label>
                    <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="hometext" name="hometext">' . $hometext . '</textarea>
                    </div>
                </div>';
    
        echo aff_editeur("hometext", "true");
    
        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-12" for="bodytext">' . __d('news', 'Texte étendu') . '</label>
                    <div class="col-12">
                    <textarea class="tin form-control" rows="25" id="bodytext" name="bodytext" >' . $bodytext . '</textarea>
                    </div>
                </div>';
    
        echo aff_editeur('bodytext', '');
    
        publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
    
        echo '
            <div class="mb-3 row">
                <input type="hidden" name="author" value="' . $aid . '" />
                <div class="col-7">
                    <select class="form-select" name="op">
                    <option value="PreviewAdminStory" selected>' . __d('news', 'Prévisualiser') . '</option>
                    <option value="PostStory">' . __d('news', 'Poster un Article Admin') . '</option>
                    </select>
                </div>
                <div class="col-5">
                    <input class="btn btn-primary" type="submit" value="' . __d('news', 'Ok') . '" />
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
        var formulid = ["storiespreviswart"];
        inpandfieldlen("subject",255);
        const choixgroupe = document.getElementById("choixgroupe");
        const mem_y = document.querySelector("#mem_y");
        const mem_n = document.querySelector("#mem_n");
        mem_y.checked ? "" : choixgroupe.style.display="none" ;
        ';
    
        adminfoot('fv', $fv_parametres, $arg1, '');
    }

}
