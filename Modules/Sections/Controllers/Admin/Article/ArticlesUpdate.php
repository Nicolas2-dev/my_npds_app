<?php

namespace Modules\Sections\Controllers\Admin\Article;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Sections\Support\Facades\Section;


class ArticlesUpdate extends AdminController
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
     * Undocumented function
     *
     * @param [type] $artid
     * @return void
     */
    public function secartupdate($artid)
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
    
        $title      = stripslashes($title);
        $content    = stripslashes(dataimagetofileurl($content, 'cache/s'));
    
        echo '
        <form id="secartupdate" action="admin.php" method="post" name="adminForm">
            <input type="hidden" name="artid" value="' . $artid . '" />
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="secid">' . __d('sections', 'Sous-rubrique') . '</label>
                <div class="col-sm-8">';
    
        // a affiner pas bon car dans certain cas on peut donc publier dans une sous rubrique sur laquelle on n'a pas les droits        
        $tmp_autorise = Section::sousrub_select($secid); 
        
        if ($tmp_autorise) {
            echo $tmp_autorise;
        } else {
            $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
            list($secname) = sql_fetch_row($result);
    
            echo '
                <strong>' . Language::aff_langue($secname) . '</strong>
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
                    ' . Editeur::aff_editeur('content', '');
    
        Section::droits($userlevel);
    
        echo $fin;
    
        echo '</form>';
    
        Css::adminfoot('', '', '', '');
    }

}
