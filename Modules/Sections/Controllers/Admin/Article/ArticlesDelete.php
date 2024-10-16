<?php

namespace Modules\Sections\Controllers\Admin\Article;

use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;
use Modules\Sections\Support\Facades\Section;


class ArticlesDelete extends AdminController
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
     * @param integer $ok
     * @return void
     */
    public function secartdelete($artid, $ok = 0)
    {
        // protection
        $result = sql_query("SELECT secid FROM seccont WHERE artid='$artid'");
        list($secid) = sql_fetch_row($result);
    
        $tmp = Section::droits_publication($secid);
    
        if (($tmp != 7) and ($tmp != 4)) {
            Header("Location: admin.php?op=sections");
        }
    
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
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la publication :') . ' <span class="text-muted">' . Language::aff_langue($title) . '</span></h3>
            <p class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous certain de vouloir effacer cette publication ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=secartdelete&amp;artid=' . $artid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" role="button" href="admin.php?op=sections" >' . __d('sections', 'Non') . '</a>
            </p>';
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
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la publication :') . ' <span class="text-muted">' . Language::aff_langue($title) . '</span></h3>
            <p class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous certain de vouloir effacer cette publication ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=secartdelete2&amp;artid=' . $artid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" role="button" href="admin.php?op=sections" >' . __d('sections', 'Non') . '</a>
            </p>';
        }
    }

}
