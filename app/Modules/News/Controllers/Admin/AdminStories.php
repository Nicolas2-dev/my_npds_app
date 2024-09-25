<?php

namespace App\Modules\News\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminStories extends AdminController
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
        // $f_meta_nom = 'adminStory';

        // include("publication.php");
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit

        // settype($catid, 'integer');

        // switch ($op) {
        
        //     case 'EditCategory':
        //         EditCategory($catid);
        //         break;
        
        //     case 'DelCategory':
        //         DelCategory($cat);
        //         break;
        
        //     case 'YesDelCategory':
        //         YesDelCategory($catid);
        //         break;
        
        //     case 'NoMoveCategory':
        //         NoMoveCategory($catid, $newcat);
        //         break;
        
        //     case 'SaveEditCategory':
        //         SaveEditCategory($catid, $title);
        //         break;
        
        //     case 'AddCategory':
        //         AddCategory();
        //         break;
        
        //     case 'SaveCategory':
        //         SaveCategory($title);
        //         break;
        
        //     case 'DisplayStory':
        //         displayStory($qid);
        //         break;
        
        //     case 'PreviewAgain':
        //         previewStory($qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
        //         break;
        
        //     case 'PostStory':
        //         settype($notes, 'string');
        //         settype($date_debval, 'string');
        //         settype($date_finval, 'string');
        //         settype($qid, 'integer');
        
        //         settype($uid, 'string'); //
        
        //         if (!$date_debval)
        //             $date_debval = $dd_pub . ' ' . $dh_pub . ':01';
        
        //         if (!$date_finval)
        //             $date_finval = $fd_pub . ' ' . $fh_pub . ':01';
        
        //         if ($date_finval < $date_debval)
        //             $date_finval = $date_debval;
        
        //         $temp_new = mktime(substr($date_debval, 11, 2), substr($date_debval, 14, 2), 0, substr($date_debval, 5, 2), substr($date_debval, 8, 2), substr($date_debval, 0, 4));
        //         $temp = time();
        
        //         if ($temp > $temp_new)
        //             postStory("pub_immediate", $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur);
        //         else
        //             postStory("pub_automated", $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur);
        //         break;
        
        //     case 'DeleteStory':
        //         deleteStory($qid);
        
        //         Header("Location: admin.php?op=submissions");
        //         break;
        
        //     case 'EditStory':
        //         editStory($sid);
        //         break;
        
        //     case 'ChangeStory':
        //         settype($fd_pub, 'string');
        //         settype($fh_pub, 'string');
        //         settype($dd_pub, 'string');
        //         settype($dh_pub, 'string');
        //         settype($Cdate, 'string');
        //         settype($Csid, 'boolean');
        
        //         $date_finval = "$fd_pub $fh_pub:00";
        
        //         changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $Cdate, $Csid, $date_finval, $epur, $theme, $dd_pub, $fd_pub, $dh_pub, $fh_pub);
        //         break;
        
        //     case 'RemoveStory':
        //         settype($ok, 'string');
        
        //         removeStory($sid, $ok);
        //         break;
        
        //     case 'adminStory':
        //         adminStory();
        //         break;
                
        //     case 'PreviewAdminStory':
        //         previewAdminStory($subject, $hometext, $bodytext, $topic, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur);
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
                    <input class="form-check-input" type="radio" id="ihome_y" name="ihome" value="0" ' . $sel1 . ' />
                    <label class="form-check-label" for="ihome_y">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="ihome_n" name="ihome" value="1" ' . $sel2 . ' />
                    <label class="form-check-label" for="ihome_n">' . __d('news', 'Non') . '</label>
                    </div>
                    <p class="help-block">' . __d('news', 'Ne s\'applique que si la catégorie : \'Articles\' n\'est pas sélectionnée.') . '</p>
                </div>
            </div>';
    
        $sel1 = '';
        $sel2 = 'checked="checked"';
    
        echo '
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label" >' . __d('news', 'Seulement aux membres') . ', ' . __d('news', 'Groupe') . '.</label>
                <div class="col-sm-8 my-2">
                    <div class="form-check form-check-inline">';
    
        //?? à revoir comprends pas ...
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
                    <input class="form-check-input" type="radio" id="mem_y" name="members" value="1" ' . $sel1 . ' />
                    <label class="form-check-label" for="mem_y">' . __d('news', 'Oui') . '</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"  id="mem_n" name="members" value="0" ' . $sel2 . ' />
                    <label class="form-check-label" for="mem_n">' . __d('news', 'Non') . '</label>
                    </div>
                </div>
            </div>';
    
        // ---- Groupes
        $mX = liste_group();
    
        $tmp_groupe = '';
    
        settype($Mmembers, 'integer');
    
        foreach ($mX as $groupe_id => $groupe_name) {
            if ($groupe_id == '0') 
                $groupe_id = '';
    
            if ($Mmembers == $groupe_id) 
                $sel3 = 'selected="selected"';
            else 
                $sel3 = '';
    
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
    
        echo '<option name="catid" value="0" ' . $sel . '>' . __d('news', 'Articles') . '</option>';
    
        while (list($catidX, $title) = sql_fetch_row($selcat)) {
            if ($catidX == $cat) 
                $sel = 'selected="selected"';
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
    
    // CATEGORIES
    function AddCategory()
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Ajouter une nouvelle Catégorie') . '</h3>
        <form id="storiesaddcat" action="admin.php" method="post">
            <div class="mb-3 row">
                <label class="col-sm-12 col-form-label" for="title">' . __d('news', 'Nom') . '</label>
                <div class="col-sm-12">
                    <input class="form-control" type="text" id="title" name="title" maxlength="255" required="required" />
                    <span class="help-block text-end" id="countcar_title"></span>
                </div>
            </div>
            <input type="hidden" name="op" value="SaveCategory" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <input class="btn btn-primary" type="submit" value="' . __d('news', 'Sauver les modifications') . '" />
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["storiesaddcat"];
        inpandfieldlen("title",255);';
    
        adminfoot('fv', '', $arg1, '');
    }
    
    function SaveCategory($title)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $title = preg_replace('#"#', '', $title);
        $check = sql_num_rows(sql_query("SELECT catid FROM stories_cat WHERE title='$title'"));
        
        if ($check)
            $what1 = '<div class="alert alert-danger lead" role="alert">' . __d('news', 'Cette Catégorie existe déjà !') . '<br /><a href="javascript:history.go(-1)" class="btn btn-secondary  mt-2">' . __d('news', 'Retour en arrière, pour changer le Nom') . '</a></div>';
        else {
            $what1 = '<div class="alert alert-success lead" role="alert">' . __d('news', 'Nouvelle Catégorie ajoutée') . '</div>';
            $result = sql_query("INSERT INTO stories_cat VALUES (NULL, '$title', '0')");
        }

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Ajouter une nouvelle Catégorie') . '</h3>
        ' . $what1;
    
        adminfoot('', '', '', '');
    }
    
    function EditCategory($catid)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Edition des Catégories') . '</h3>';
    
        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
        list($title) = sql_fetch_row($result);
    
        if (!$catid) {
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="catid">' . __d('news', 'Sélectionner une Catégorie') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="catid" name="catid">';
    
            echo '<option name="catid" value="0">' . __d('news', 'Articles') . '</option>';
    
            while (list($catid, $title) = sql_fetch_row($selcat)) {
                echo '
                   <option name="catid" value="' . $catid . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                    </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="op" value="EditCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Editer') . '" />
                    </div>
                </div>
            </form>';
    
            adminfoot('', '', '', '');
        } else {
            echo '
            <form id="storieseditcat" action="admin.php" method="post">
                <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="title">' . __d('news', 'Nom') . '</label>
                    <div class="col-sm-12">
                        <input class="form-control" type="text" id="title" name="title" maxlength="255" value="' . $title . '" required="required"/>
                        <span class="help-block text-end" id="countcar_title"></span>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="catid" value="' . $catid . '" />
                        <input type="hidden" name="op" value="SaveEditCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Sauver les modifications') . '" />
                    </div>
                </div>
            </form>';
    
            $arg1 = '
            var formulid = ["storieseditcat"];
            inpandfieldlen("title",255);';
    
            adminfoot('fv', '', $arg1, '');
        }
    }
    
    function SaveEditCategory($catid, $title)
    {
        $f_titre = __d('news', 'Articles');
        $title = preg_replace('#"#', '', $title);
    
        $check = sql_num_rows(sql_query("SELECT catid FROM stories_cat WHERE title='$title'"));
    
        if ($check) {
            $what1 = '<div class="alert alert-danger lead" role="alert">' . __d('news', 'Cette Catégorie existe déjà !') . '<br /><a href="javascript:history.go(-2)" class="btn btn-secondary  mt-2">' . __d('news', 'Retour en arrière, pour changer le Nom') . '</a></div>';
        } else {
            $what1 = '<div class="alert alert-success lead" role="alert">' . __d('news', 'Catégorie sauvegardée') . '</div>';
    
            $result = sql_query("UPDATE stories_cat SET title='$title' WHERE catid='$catid'");
    
            global $aid;
            Ecr_Log("security", "SaveEditCategory($catid, $title) by AID : $aid", "");
        }

        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Edition des Catégories') . '</h3>
        ' . $what1;
    
        adminfoot('', '', '', '');
    }
    
    function DelCategory($cat)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$cat'");
        list($title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3 text-danger">' . __d('news', 'Supprimer une Catégorie') . '</h3>';
    
        if (!$cat) {
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="cat">' . __d('news', 'Sélectionner une Catégorie à supprimer') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="cat" name="cat">';
    
            while (list($catid, $title) = sql_fetch_row($selcat)) {
                echo '<option name="cat" value="' . $catid . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="op" value="DelCategory" />
                        <button class="btn btn-danger" type="submit">' . __d('news', 'Effacer') . '</button>
                    </div>
                </div>
            </form>';
    
        } else {
            $result2 = sql_query("SELECT * FROM stories WHERE catid='$cat'");
            $numrows = sql_num_rows($result2);
    
            if ($numrows == 0) {
                sql_query("DELETE FROM stories_cat WHERE catid='$cat'");
    
                global $aid;
                Ecr_Log('security', "DelCategory($cat) by AID : $aid", '');
    
                echo '<div class="alert alert-success" role="alert">' . __d('news', 'Suppression effectuée') . '</div>';
            } else {
                echo '
                <div class="alert alert-danger lead" role="alert">
                    <p class="noir"><strong>' . __d('news', 'Attention : ') . '</strong> ' . __d('news', 'la Catégorie') . ' <strong>' . $title . '</strong> ' . __d('news', 'a') . ' <strong>' . $numrows . '</strong> ' . __d('news', 'Articles !') . '<br />';
                
                echo __d('news', 'Vous pouvez supprimer la Catégorie, les Articles et Commentaires') . ' ';
                
                echo __d('news', 'ou les affecter à une autre Catégorie.') . '<br /></p>
                    <p align="text-center"><strong>' . __d('news', 'Que voulez-vous faire ?') . '</strong></p>
                </div>
                <a href="admin.php?op=YesDelCategory&amp;catid=' . $cat . '" class="btn btn-outline-danger">' . __d('news', 'Tout supprimer') . '</a>
                <a href="admin.php?op=NoMoveCategory&amp;catid=' . $cat . '" class="btn btn-outline-primary">' . __d('news', 'Affecter à une autre Catégorie') . '</a></p>';
            }
        }
    
        adminfoot('', '', '', '');
    }
    
    function YesDelCategory($catid)
    {
        sql_query("DELETE FROM stories_cat WHERE catid='$catid'");
        $result = sql_query("SELECT sid FROM stories WHERE catid='$catid'");
        
        while (list($sid) = sql_fetch_row($result)) {
            sql_query("DELETE FROM stories WHERE catid='$catid'");
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$topic'");
            }
        }
    
        global $aid;
        Ecr_Log('security', "YesDelCategory($catid) by AID : $aid", '');
    
        Header("Location: admin.php");
    }
    
    function NoMoveCategory($catid, $newcat)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $result = sql_query("SELECT title FROM stories_cat WHERE catid='$catid'");
        list($title) = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('news', 'Affectation d\'Articles vers une nouvelle Catégorie') . '</h3>';
    
        if (!$newcat) {
            echo '<label>' . __d('news', 'Tous les Articles dans') . ' <strong>' . aff_langue($title) . '</strong> ' . __d('news', 'seront affectés à') . '</label>';
            
            $selcat = sql_query("SELECT catid, title FROM stories_cat");
    
            echo '
            <form action="admin.php" method="post">
                <div class="mb-3 row">
                    <label class="col-form-label visually-hidden" for="newcat">' . __d('news', 'Sélectionner la nouvelle Catégorie : ') . '</label>
                    <div class="col-sm-12">
                        <select class="form-select" id="newcat" name="newcat">
                        <option name="newcat" value="0">' . __d('news', 'Articles') . '</option>';
    
            while (list($newcat, $title) = sql_fetch_row($selcat)) {
                echo '<option name="newcat" value="' . $newcat . '">' . aff_langue($title) . '</option>';
            }
    
            echo '
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <input type="hidden" name="catid" value="' . $catid . '" />
                        <input type="hidden" name="op" value="NoMoveCategory" />
                        <input class="btn btn-primary" type="submit" value="' . __d('news', 'Affectation') . '" />
                    </div>
                </div>
            </form>';
    
        } else {
            $resultm = sql_query("SELECT sid FROM stories WHERE catid='$catid'");
    
            while (list($sid) = sql_fetch_row($resultm)) {
                sql_query("UPDATE stories SET catid='$newcat' WHERE sid='$sid'");
            }
    
            sql_query("DELETE FROM stories_cat WHERE catid='$catid'");
    
            global $aid;
            Ecr_Log("security", "NoMoveCategory($catid, $newcat) by AID : $aid", "");
            echo '<div class="alert alert-success"><strong>' . __d('news', 'La ré-affectation est terminée !') . '</strong></div>';
    
        }
    
        adminfoot('', '', '', '');
    }
    
    // NEWS
    function displayStory($qid)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');
    
        $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";
    
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
    
    function previewStory($qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Articles');

        $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";
    
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
    
    function postStory($type_pub, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $date_debval, $date_finval, $epur)
    {
        if ($uid == 1) 
            $author = '';
    
        if ($hometext == $bodytext) 
            $bodytext = '';
    
        $artcomplet = array('hometext' => $hometext, 'bodytext' => $bodytext, 'notes' => $notes);
        $rechcacheimage = '#cache/(a[i|c|n]\d+_\d+_\d+.[a-z]{3,4})\\\"#m';
    
        foreach ($artcomplet as $k => $artpartie) {
            preg_match_all($rechcacheimage, $artpartie, $cacheimages);
    
            foreach ($cacheimages[1] as $imagecache) {
                rename("cache/" . $imagecache, "modules/upload/upload/" . $imagecache);
    
                $$k = preg_replace($rechcacheimage, 'modules/upload/upload/\1"', $artpartie, 1);
            }
        }
    
        $subject = stripslashes(FixQuotes(str_replace('"', '&quot;', $subject)));
    
        $hometext = dataimagetofileurl($hometext, 'modules/upload/upload/ai');
        $bodytext = dataimagetofileurl($bodytext, 'modules/upload/upload/ac');
        $notes = dataimagetofileurl($notes, 'modules/upload/upload/an');
    
        $hometext = stripslashes(FixQuotes($hometext));
        $bodytext = stripslashes(FixQuotes($bodytext));
        $notes = stripslashes(FixQuotes($notes));
    
        if (($members == 1) and ($Mmembers == '')) 
            $ihome = '-127';
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) 
            $ihome = $Mmembers;
    
        if ($type_pub == 'pub_immediate') {
            $result = sql_query("INSERT INTO stories VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic','$author', '$notes', '$ihome', '0', '$date_finval','$epur')");
            
            Ecr_Log("security", "postStory (pub_immediate, $subject) by AID : $aid", "");
        } else {
            $result = sql_query("INSERT INTO autonews VALUES (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome','$date_debval','$date_finval','$epur')");
            
            Ecr_Log("security", "postStory (autonews, $subject) by AID : $aid", "");
        }
    
        if (($uid != 1) and ($uid != ''))
            sql_query("UPDATE users SET counter=counter+1 WHERE uid='$uid'");
    
        sql_query("UPDATE authors SET counter=counter+1 WHERE aid='$aid'");
    
        if (Config::get('npds.ultramode'))
            ultramode();
    
        deleteStory($qid);
    
        if ($type_pub == 'pub_immediate') {
    
            if (Config::get('npds.subscribe'))
                subscribe_mail("topic", $topic, '', $subject, '');
    
            // Cluster Paradise
            if (file_exists("modules/cluster-paradise/cluster-activate.php")) 
                include("modules/cluster-paradise/cluster-activate.php");
    
            if (file_exists("modules/cluster-paradise/cluster-M.php")) 
                include("modules/cluster-paradise/cluster-M.php");
    
            // Cluster Paradise
            // Réseaux sociaux
            if (file_exists('modules/App_twi/App_to_twi.php')) 
                include('modules/App_twi/App_to_twi.php');
    
            if (file_exists('modules/App_fbk/App_to_fbk.php')) 
                include('modules/App_twi/App_to_fbk.php');
            // Réseaux sociaux
        }
    
        redirect_url("admin.php?");
    }
    
    function editStory($sid)
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Editer un Article');

        if (($sid == '') or ($sid == '0'))
            header("location: admin.php");
    
        $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";
    
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
    
    function deleteStory($qid)
    {
        $res = sql_query("SELECT story, bodytext FROM queue WHERE qid='$qid'");
        list($story, $bodytext) = sql_fetch_row($res);
    
        $artcomplet = $story . $bodytext;
        $rechcacheimage = '#cache/a[i|c|]\d+_\d+_\d+.[a-z]{3,4}#m';
    
        preg_match_all($rechcacheimage, $artcomplet, $cacheimages);
    
        foreach ($cacheimages[0] as $imagetodelete) {
            unlink($imagetodelete);
        }
    
        $result = sql_query("DELETE FROM queue WHERE qid='$qid'");
    
        global $aid;
        Ecr_Log("security", "deleteStoryfromQueue($qid) by AID : $aid", "");
    }
    
    function removeStory($sid, $ok = 0)
    {
        if (($sid == '') or ($sid == '0'))
            header("location: admin.php");
    
        $result = sql_query("SELECT topic FROM stories WHERE sid='$sid'");
        list($topic) = sql_fetch_row($result);
    
        $affiche = false;
    
        $result2 = sql_query("SELECT topicadmin, topicname FROM topics WHERE topicid='$topic'");
        list($topicadmin, $topicname) = sql_fetch_row($result2);
    
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
    
        if ($ok) {
            $res = sql_query("SELECT hometext, bodytext, notes FROM stories WHERE sid='$sid'");
            list($hometext, $bodytext, $notes) = sql_fetch_row($res);
    
            $artcomplet = $hometext . $bodytext . $notes;
            $rechuploadimage = '#modules/upload/upload/a[i|c|]\d+_\d+_\d+.[a-z]{3,4}#m';
    
            preg_match_all($rechuploadimage, $artcomplet, $uploadimages);
    
            foreach ($uploadimages[0] as $imagetodelete) {
                unlink($imagetodelete);
            }
    
            sql_query("DELETE FROM stories WHERE sid='$sid'");
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' AND topic_id='$topic'");
            }
    
            global $aid;
            Ecr_Log('security', "removeStory ($sid, $ok) by AID : $aid", '');
    
            if (Config::get('npds.ultramode'))
                ultramode();
    
            Header("Location: admin.php");
        } else {

            $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";

            echo '
            <div class="alert alert-danger">' . __d('news', 'Etes-vous sûr de vouloir effacer l\'Article N°') . ' ' . $sid . ' ' . __d('news', 'et tous ses Commentaires ?') . '</div>
            <p class=""><a href="admin.php?op=RemoveStory&amp;sid=' . $sid . '&amp;ok=1" class="btn btn-danger" >' . __d('news', 'Oui') . '</a>&nbsp;<a href="admin.php" class="btn btn-secondary" >' . __d('news', 'Non') . '</a></p>';
        }
    }
    
    function changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $members, $Mmembers, $Cdate, $Csid, $date_finval, $epur, $theme, $dd_pub, $fd_pub, $dh_pub, $fh_pub)
    {
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
    
    function adminStory()
    {
        $f_meta_nom = 'adminStory';
        $f_titre = __d('news', 'Nouvel Article');
        $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";
    
        settype($hometext, 'string');
        settype($bodytext, 'string');
        settype($dd_pub, 'string');
        settype($fd_pub, 'string');
        settype($dh_pub, 'string');
        settype($fh_pub, 'string');
        settype($epur, 'integer');
        settype($ihome, 'integer');
        settype($sel, 'string');
        settype($topic, 'string');
    
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
    
    function previewAdminStory($subject, $hometext, $bodytext, $topic, $catid, $ihome, $members, $Mmembers, $dd_pub, $fd_pub, $dh_pub, $fh_pub, $epur)
    {
        global $topicimage;
    
        $hlpfile = "language/manuels/Config::get('npds.language')/newarticle.html";
    
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
