<?php

namespace Modules\Sections\Controllers\Admin\Article;

use Modules\Npds\Core\AdminController;


class Articles extends AdminController
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

    function secartedit($artid)
    {
        $result2 = sql_query("SELECT author, artid, secid, title, content, userlevel FROM seccont WHERE artid='$artid'");
        list($author, $artid, $secid, $title, $content, $userlevel) = sql_fetch_row($result2);
    
        if (!$artid)
            Header("Location: admin.php?op=sections");

        $title = stripslashes($title);
        $content = stripslashes(dataimagetofileurl($content, 'cache/s'));
    
        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Editer une publication') . '</h3>
            <form action="admin.php" method="post" id="secartedit" name="adminForm">
                <input type="hidden" name="artid" value="' . $artid . '" />
                <input type="hidden" name="op" value="secartchange" />
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-4" for="secid">' . __d('sections', 'Sous-rubriques') . '</label>
                    <div class="col-sm-8">';
    
        // la on déraille ???
        $tmp_autorise = sousrub_select($secid);
        if ($tmp_autorise)
            echo $tmp_autorise;
        else {
            $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
            list($secname) = sql_fetch_row($result);
            echo "<b>" . aff_langue($secname) . "</b>";
            echo '<input type="hidden" name="secid" value="' . $secid . '" />';
        }
    
        echo '
                    </div>
                </div>';
    
        if ($tmp_autorise)
            echo '<a href="admin.php?op=publishcompat&amp;article=' . $artid . '">' . __d('sections', 'Publications connexes') . '</a>';
    
        echo '
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="title">' . __d('sections', 'Titre') . '</label>
                    <div class="col-sm-12">
                    <textarea class="form-control" id="title" name="title" rows="2">' . $title . '</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-12" for="content">' . __d('sections', 'Contenu') . '</label>
                    <div class="col-sm-12">
                    <textarea class="tin form-control" id="content" name="content" rows="30" >' . $content . '</textarea>
                    </div>
                </div>';
    
        echo aff_editeur('content', '');
    
        echo '
                <div class="mb-3 row">
                <div class="col-sm-12">';
    
        droits($userlevel);
    
        $droits_pub = droits_publication($secid);
    
        if ($droits_pub == 3 or $droits_pub == 7) 
            echo '<input class="btn btn-primary" type="submit" value="' . __d('sections', 'Enregistrer') . '" />&nbsp;';
        
        echo '
                    <input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
                </div>
            </div>
        </form>';
    
        adminfoot('', '', '', '');
    }
    
    function secartupdate($artid)
    {
        $result = sql_query("SELECT author, artid, secid, title, content, userlevel FROM seccont_tempo WHERE artid='$artid'");
        list($author, $artid, $secid, $title, $content, $userlevel) = sql_fetch_row($result);
    
        $testpubli = sql_query("SELECT type FROM publisujet WHERE secid2='$secid' AND aid='$aid' AND type='1'");
        list($test_publi) = sql_fetch_row($testpubli);
    
        if ($test_publi == 1) {
            $debut = '<div class="alert alert-info">' . __d('sections', 'Vos droits de publications vous permettent de mettre à jour ou de supprimer ce contenu mais pas de la mettre en ligne sur le site.') . '</div>';
            
            $fin = '
            <div class="mb-3 row">
                <div class="col-12">
                    <select class="form-select" name="op">
                    <option value="secartchangeup" selected="selected">' . __d('sections', 'Mettre à jour') . '</option>
                    <option value="secartdelete2">' . __d('sections', 'Supprimer') . '</option>
                    </select>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" name="submit" value="' . __d('sections', 'Ok') . '" />';
        }
    
        $testpubli = sql_query("SELECT type FROM publisujet WHERE secid2='$secid' AND aid='$aid' AND type='2'");
        list($test_publi) = sql_fetch_row($testpubli);
    
        if (($test_publi == 2) or ($radminsuper == 1)) {
            $debut = '<div class="alert alert-success">' . __d('sections', 'Vos droits de publications vous permettent de mettre à jour, de supprimer ou de le mettre en ligne sur le site ce contenu.') . '<br /></div>';
            
            $fin = '
            <div class="mb-3 row">
                <div class="col-12">
                    <select class="form-select" name="op">
                    <option value="secartchangeup" selected="selected">' . __d('sections', 'Mettre à jour') . '</option>
                    <option value="secartdelete2">' . __d('sections', 'Supprimer') . '</option>
                    <option value="secartpublish">' . __d('sections', 'Publier') . '</option>
                    </select>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" name="submit" value="' . __d('sections', 'Ok') . '" />';
        }
    
        $fin .= '&nbsp;<input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />';

        echo '
        <hr />
        <h3 class="mb-3">' . __d('sections', 'Editer une publication') . '</h3>';
    
        echo $debut;
    
        $title = stripslashes($title);
        $content = stripslashes(dataimagetofileurl($content, 'cache/s'));
    
        echo '
        <form id="secartupdate" action="admin.php" method="post" name="adminForm">
            <input type="hidden" name="artid" value="' . $artid . '" />
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="secid">' . __d('sections', 'Sous-rubrique') . '</label>
                <div class="col-sm-8">';
    
        $tmp_autorise = sousrub_select($secid); /// a affiner pas bon car dans certain cas on peut donc publier dans une sous rubrique sur laquelle on n'a pas les droits
        
        if ($tmp_autorise)
            echo $tmp_autorise;
        else {
            $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
            list($secname) = sql_fetch_row($result);
    
            echo '
                <strong>' . aff_langue($secname) . '</strong>
                <input type="hidden" name="secid" value="' . $secid . '" />';
        }
    
        echo '
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="title">' . __d('sections', 'Titre') . '</label>
                <div class=" col-12">
                    <textarea class="form-control" id="title" name="title" rows="2">' . $title . '</textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-form-label col-12" for="content">' . __d('sections', 'Contenu') . '</label>
                <div class=" col-12">
                    <textarea class="tin form-control" id="content" name="content" rows="30">' . $content . '</textarea>
                </div>
            </div>
                    ' . aff_editeur('content', '');
    
        droits($userlevel);
    
        echo $fin;
    
        echo '</form>';
    
        adminfoot('', '', '', '');
    }
    
    function secarticleadd($secid, $title, $content, $autho, $members, $Mmembers)
    {
        // pas de removehack pour l'entrée des données ???????
        if (is_array($Mmembers) and ($members == 1))
            $members = implode(',', $Mmembers);
    
        $title = stripslashes(FixQuotes($title));
    
        if ($secid != "0") {
            if ($radminsuper == 1) {
                $timestamp = time();
    
                $content = stripslashes(FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/s')));
    
                sql_query("INSERT INTO seccont VALUES (NULL,'$secid','$title','$content','0','$autho','99','$members', '$timestamp')");
    
                global $aid;
                Ecr_Log("security", "CreateArticleSections($secid, $title) by AID : $aid", "");
            } else {
                $content = stripslashes(FixQuotes(dataimagetofileurl($content, 'cache/s')));
    
                sql_query("INSERT INTO seccont_tempo VALUES (NULL,'$secid','$title','$content','0','$autho','99','$members')");
    
                global $aid;
                Ecr_Log('security', "CreateArticleSectionsTempo($secid, $title) by AID : $aid", '');
            }
        }
    
        Header("Location: admin.php?op=sections");
    }
    
    function secartchange($artid, $secid, $title, $content, $members, $Mmembers)
    {
        if (is_array($Mmembers) and ($members == 1))
            $members = implode(',', $Mmembers);
    
        $title = stripslashes(FixQuotes($title));
        $content = stripslashes(FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/s')));
        $timestamp = time();
    
        if ($secid != '0') {
            sql_query("UPDATE seccont SET secid='$secid', title='$title', content='$content', userlevel='$members', timestamp='$timestamp' WHERE artid='$artid'");
            
            global $aid;
            Ecr_Log("security", "UpdateArticleSections($artid, $secid, $title) by AID : $aid", "");
        }
    
        Header("Location: admin.php?op=secartedit&artid=$artid");
    }
    
    function secartchangeup($artid, $secid, $title, $content, $members, $Mmembers)
    {
        if (is_array($Mmembers) and ($members == 1))
            $members = implode(',', $Mmembers);
    
        $title = stripslashes(FixQuotes($title));
        $content = stripslashes(FixQuotes(dataimagetofileurl($content, 'cache/s')));
    
        if ($secid != '0') {
            sql_query("UPDATE seccont_tempo SET secid='$secid', title='$title', content='$content', userlevel='$members' WHERE artid='$artid'");
            
            global $aid;
            Ecr_Log('security', "UpdateArticleSectionsTempo($artid, $secid, $title) by AID : $aid", '');
        }
    
        Header("Location: admin.php?op=secartupdate&artid=$artid");
    }
    
    function secartpublish($artid, $secid, $title, $content, $author, $members, $Mmembers)
    {
        if (is_array($Mmembers) and ($members == 1))
            $members = implode(',', $Mmembers);
    
        $content = stripslashes(FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/s')));
        $title = stripslashes(FixQuotes($title));
    
        if ($secid != '0') {
            sql_query("DELETE FROM seccont_tempo WHERE artid='$artid'");
            $timestamp = time();
    
            sql_query("INSERT INTO seccont VALUES (NULL,'$secid','$title','$content', '0', '$author', '99', '$members', '$timestamp')");
            
            global $aid;
            Ecr_Log('security', "PublicateArticleSections($artid, $secid, $title) by AID : $aid", '');
    
            $result = sql_query("SELECT email FROM authors WHERE aid='$author'");
            list($lemail) = sql_fetch_row($result);
    
            $sujet = html_entity_decode(__d('sections', 'Validation de votre publication'), ENT_COMPAT | ENT_HTML401, cur_charset);
            $message = __d('sections', 'La publication que vous aviez en attente vient d\'être validée');
    
            send_email($lemail, $sujet, $message, Config::get('npds.notify_from'), true, "html", '');
        }
    
        Header("Location: admin.php?op=sections");
    }

    function secartdelete($artid, $ok = 0)
    {
        // protection
        $result = sql_query("SELECT secid FROM seccont WHERE artid='$artid'");
        list($secid) = sql_fetch_row($result);
    
        $tmp = droits_publication($secid);
    
        if (($tmp != 7) and ($tmp != 4))
            Header("Location: admin.php?op=sections");
    
        if ($ok == 1) {
            $res = sql_query("SELECT content FROM seccont WHERE artid='$artid'");
            list($content) = sql_fetch_row($res);
    
            $rechuploadimage = '#modules/upload/upload/s\d+_\d+_\d+.[a-z]{3,4}#m';
    
            preg_match_all($rechuploadimage, $content, $uploadimages);
            foreach ($uploadimages[0] as $imagetodelete) {
                unlink($imagetodelete);
            }
    
            sql_query("DELETE FROM seccont WHERE artid='$artid'");
            sql_query("DELETE FROM compatsujet WHERE id1='$artid'");
    
            global $aid;
            Ecr_Log("security", "DeleteArticlesSections($artid) by AID : $aid", "");
    
            Header("Location: admin.php?op=sections");
        } else {
            $result = sql_query("SELECT title FROM seccont WHERE artid='$artid'");
            list($title) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la publication :') . ' <span class="text-muted">' . aff_langue($title) . '</span></h3>
            <p class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous certain de vouloir effacer cette publication ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=secartdelete&amp;artid=' . $artid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" role="button" href="admin.php?op=sections" >' . __d('sections', 'Non') . '</a>
            </p>';
    
            include("footer.php");
        }
    }
    
    function secartdelete2($artid, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM seccont_tempo WHERE artid='$artid'");
    
            global $aid;
            Ecr_Log('security', "DeleteArticlesSectionsTempo($artid) by AID : $aid", '');
    
            Header("Location: admin.php?op=sections");
        } else {
            $result = sql_query("SELECT title FROM seccont_tempo WHERE artid='$artid'");
            list($title) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la publication :') . ' <span class="text-muted">' . aff_langue($title) . '</span></h3>
            <p class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous certain de vouloir effacer cette publication ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=secartdelete2&amp;artid=' . $artid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" role="button" href="admin.php?op=sections" >' . __d('sections', 'Non') . '</a>
            </p>';
    
            include("footer.php");
        }
    }

}
