<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesDisplay extends AdminController
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
        $this->f_titre = __d('news', 'Articles');

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
     * @param [type] $qid
     * @return void
     */
    public function displayStory($qid)
    {
        $result = sql_query("SELECT qid, uid, uname, subject, story, bodytext, topic, date_debval,date_finval,auto_epur FROM queue WHERE qid='$qid'");
        list($qid, $uid, $uname, $subject, $story, $bodytext, $topic, $date_debval, $date_finval, $epur) = sql_fetch_row($result);
        sql_free_result($result);
    
        $subject = stripslashes($subject);
        $story = stripslashes($story);
        $bodytext = stripslashes($bodytext);
    
        if ($topic < 1) {
            $topic = 1;
        }
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topictext, topicimage, topicadmin FROM topics WHERE topicid='$topic'");
        list($topictext, $topicimage, $topicadmin) = sql_fetch_row($result2);
    
        if ($radminsuper) {
            $affiche = true;
        } else {
            $topicadminX = explode(',', $topicadmin);
    
            for ($i = 0; $i < count($topicadminX); $i++) {
                if (trim($topicadminX[$i]) == $aid) $affiche = true;
            }
        }
    
        if (!$affiche) {
            header("location: admin.php?op=submissions");
        }
    
        $topiclogo = '<span class="badge bg-secondary float-end"><strong>' . aff_langue($topictext) . '</strong></span>';

        echo '
        <hr />
        <h3>' . __d('news', 'Prévisualiser l\'Article') . '</h3>
        <form action="admin.php" method="post" name="adminForm" id="adminForm">
            <label class="col-form-label">' . __d('news', 'Langue de Prévisualisation') . '</label>
            ' . aff_localzone_langue("local_user_language") . '
            <div class="card card-body mb-3">';
    
        if ($topicimage !== '') {
            if (!$imgtmp = theme_image('topics/' . $topicimage)) {
                $imgtmp = Config::get('npds.tipath') . $topicimage;
            }
    
            $timage = $imgtmp;
    
            if (file_exists($imgtmp))
                $topiclogo = '<img class="img-fluid n-sujetsize" src="' . $timage . '" align="right" alt="" />';
        }
    
        code_aff('<h4>' . $subject . $topiclogo . '</h4>', '<div class="text-muted">' . meta_lang($story) . '</div>', meta_lang($bodytext), "");
    
        echo '
                </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="author">' . userpopover($uname, 40, '') . __d('news', 'Utilisateur') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="author" name="author" value="' . $uname . '" />
                    <a href="replypmsg.php?send=' . urlencode($uname) . '" target="_blank" title="' . __d('news', 'Diffusion d\'un Message Interne') . '" data-bs-toggle="tooltip"><i class="far fa-envelope fa-lg"></i></a>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="subject">' . __d('news', 'Titre') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="subject" name="subject" value="' . $subject . '" required="required" />
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
    
            if ($radminsuper) {
                $affiche = true;
            } else {
                $topicadminX = explode(',', $topicadmin);
    
                for ($i = 0; $i < count($topicadminX); $i++) {
                    if (trim($topicadminX[$i]) == $aid) $affiche = true;
                }
            }
    
            if ($affiche) {
                if ($topicid == $topic) {
                    $sel = 'selected="selected" ';
                }
    
                echo '<option ' . $sel . ' value="' . $topicid . '">' . aff_langue($topics) . '</option>';
    
                $sel = '';
            }
        }
    
        echo '
                    </select>
                </div>
            </div>';
    
        settype($cat, 'string');
    
        SelectCategory($cat);
    
        settype($ihome, 'integer');
        puthome($ihome);
    
        echo '
        <div class="mb-3 row">
            <label class="col-form-label col-12" for="hometext">' . __d('news', 'Texte d\'introduction') . '</label>
            <div class="col-12">
                <textarea class="tin form-control" rows="25" id="hometext" name="hometext">' . $story . '</textarea>
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
    
        echo '
        <div class="mb-3 row">
            <label class="col-form-label col-12" for="notes">' . __d('news', 'Notes') . '</label>
            <div class="col-12">
                <textarea class="tin form-control" rows="7" id="notes" name="notes"></textarea>
            </div>
        </div>';
    
        echo aff_editeur('notes', '');
    
        $dd_pub = substr($date_debval, 0, 10);
        $fd_pub = substr($date_finval, 0, 10);
        $dh_pub = substr($date_debval, 11, 5);
        $fh_pub = substr($date_finval, 11, 5);
    
        publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
    
        echo '
            <input type="hidden" name="qid" value="' . $qid . '" />
            <input type="hidden" name="uid" value="' . $uid . '" />
            <div class="mb-3">
                <select class="form-select" name="op">
                    <option value="DeleteStory">' . __d('news', 'Effacer l\'Article') . '</option>
                    <option value="PreviewAgain" selected="selected">' . __d('news', 'Re-prévisualiser') . '</option>
                    <option value="PostStory">' . __d('news', 'Poster un Article ') . '</option>
                </select>
            </div>
            <input class="btn btn-primary" type="submit" value="' . __d('news', 'Ok') . '" />
        </form>';
    
        $arg1 = '
        var formulid = ["adminForm"];';
    
        adminfoot('fv', '', $arg1, '');
    }

}
