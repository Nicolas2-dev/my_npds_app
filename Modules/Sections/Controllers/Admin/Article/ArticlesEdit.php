<?php

namespace Modules\Sections\Controllers\Admin\Article;

use PgSql\Lob;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Sections\Support\Facades\Section;

class ArticlesEdit extends AdminController
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
    public function secartedit($artid)
    {
        $result2 = sql_query("SELECT author, artid, secid, title, content, userlevel FROM seccont WHERE artid='$artid'");
        list($author, $artid, $secid, $title, $content, $userlevel) = sql_fetch_row($result2);
    
        if (!$artid) {
            Header("Location: admin.php?op=sections");
        }

        $title      = stripslashes($title);
        $content    = stripslashes(dataimagetofileurl($content, 'cache/s'));
    
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
        $tmp_autorise = Section::sousrub_select($secid);

        if ($tmp_autorise) {
            echo $tmp_autorise;
        } else {
            $result = sql_query("SELECT secname FROM sections WHERE secid='$secid'");
            list($secname) = sql_fetch_row($result);
            
            echo "<b>" . Language::aff_langue($secname) . "</b>";
            echo '<input type="hidden" name="secid" value="' . $secid . '" />';
        }
    
        echo '
                    </div>
                </div>';
    
        if ($tmp_autorise) {
            echo '<a href="admin.php?op=publishcompat&amp;article=' . $artid . '">' . __d('sections', 'Publications connexes') . '</a>';
        }

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
    
        echo Editeur::aff_editeur('content', '');
    
        echo '
                <div class="mb-3 row">
                <div class="col-sm-12">';
    
        Section::droits($userlevel);
    
        $droits_pub = Section::droits_publication($secid);
    
        if ($droits_pub == 3 or $droits_pub == 7) { 
            echo '<input class="btn btn-primary" type="submit" value="' . __d('sections', 'Enregistrer') . '" />&nbsp;';
        }
        
        echo '
                    <input class="btn btn-secondary" type="button" value="' . __d('sections', 'Retour en arrière') . '" onclick="javascript:history.back()" />
                </div>
            </div>
        </form>';
    
        Css::adminfoot('', '', '', '');
    }

}
