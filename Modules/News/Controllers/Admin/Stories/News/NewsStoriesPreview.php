<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesPreview extends AdminController
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
     * @param [type] $uid
     * @param [type] $author
     * @param [type] $subject
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $notes
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
    public function previewStory($qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
    {
        $subject = stripslashes(str_replace('"', '&quot;', $subject));
        $hometext = stripslashes(dataimagetofileurl($hometext, 'cache/ai'));
        $bodytext = stripslashes(dataimagetofileurl($bodytext, 'cache/ac'));
        $notes = stripslashes(dataimagetofileurl($notes, 'cache/an'));
    
        if ($topic < 1) {
            $topic = 1;
        }
    
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
    
        if (!$affiche) {
            header("location: admin.php?op=submissions");
        }
    
        $topiclogo = '<span class="badge bg-secondary float-end"><strong>' . aff_langue($topictext) . '</strong></span>';

        global $local_user_language;
    
        echo '
        <hr />
        <h3>' . __d('news', 'Prévisualiser l\'Article') . '</h3>
        <form action="admin.php" method="post" name="adminForm">
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
    
        code_aff('<h3>' . $subject . $topiclogo . '</h3>', '<div class="text-muted">' . meta_lang($hometext) . '</div>', meta_lang($bodytext), meta_lang($notes));
    
        echo '
                </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="author">' . __d('news', 'Utilisateur') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="author" name="author" value="' . $author . '" />
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" for="subject">' . __d('news', 'Titre') . '</label>
                <div class="col-sm-8">
                    <input class="form-control" type="text" id="subject" name="subject" value="' . $subject . '" />
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
                    if (trim($topicadminX[$i]) == $aid) 
                        $affiche = true;
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
                <textarea class="tin form-control" cols="70" rows="25" id="hometext" name="hometext" >' . $hometext . '</textarea>
            </div>
        </div>';
    
        echo aff_editeur('hometext', '');
    
        echo '
        <div class="mb-3 row">
            <label class="col-form-label col-12" for="bodytext">' . __d('news', 'Texte étendu') . '</label>
            <div class="col-12">
                <textarea class="tin form-control" cols="70" rows="25" id="bodytext" name="bodytext" >' . $bodytext . '</textarea>
            </div>
        </div>';
    
        echo aff_editeur('bodytext', '');
    
        echo '
        <div class="mb-3 row">
            <label class="col-form-label col-12" for="notes">' . __d('news', 'Notes') . '</label>
            <div class="col-12">
                <textarea class="tin form-control" cols="70" rows="7" id="notes" name="notes" >' . $notes . '</textarea>
            </div>
        </div>';
    
        echo aff_editeur('notes', '');
    
        publication($dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
    
        echo '
            <input type="hidden" name="qid" value="' . $qid . '" />
            <input type="hidden" name="uid" value="' . $uid . '" />
            <select class="form-select" name="op">
                <option value="DeleteStory">' . __d('news', 'Effacer l\'Article') . '</option>
                <option value="PreviewAgain" selected="selected">' . __d('news', 'Re-prévisualiser') . '</option>
                <option value="PostStory">' . __d('news', 'Poster un Article') . '</option>
            </select>
            <input class="btn btn-primary my-2" type="submit" value="' . __d('news', 'Ok') . '" />
        </form>';
    
        adminfoot('', '', '', '');
    }

}
