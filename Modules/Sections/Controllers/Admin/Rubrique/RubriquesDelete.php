<?php

namespace Modules\Sections\Controllers\Admin\Rubrique;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class RubriquesDelete extends AdminController
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
     * @param [type] $rubid
     * @param integer $ok
     * @return void
     */
    public function rubriquedelete($rubid, $ok = 0)
    {
        if (!$radminsuper) {
            Header("Location: admin.php?op=sections");
        }
    
        if ($ok == 1) {
            $result = sql_query("SELECT secid FROM sections WHERE rubid='$rubid'");
    
            if (sql_num_rows($result) > 0) {
    
                while (list($secid) = sql_fetch_row($result)) {
                    $result2 = sql_query("SELECT artid FROM seccont WHERE secid='$secid'");
    
                    if (sql_num_rows($result2) > 0) {
                        while (list($artid) = sql_fetch_row($result2)) {
                            sql_query("DELETE FROM seccont WHERE artid='$artid'");
                            sql_query("DELETE FROM compatsujet WHERE id1='$artid'");
                        }
                    }
                }
            }
    
            sql_query("DELETE FROM sections WHERE rubid='$rubid'");
            sql_query("DELETE FROM rubriques WHERE rubid='$rubid'");
    
            global $aid;
            Ecr_Log("security", "DeleteRubriques($rubid) by AID : $aid", "");
    
            Header("Location: admin.php?op=sections");
        } else {

            $result = sql_query("SELECT rubname FROM rubriques WHERE rubid='$rubid'");
            list($rubname) = sql_fetch_row($result);
    
            echo '
            <hr />
            <h3 class="mb-3 text-danger">' . __d('sections', 'Effacer la Rubrique : ') . '<span class="text-muted">' . Language::aff_langue($rubname) . '</span></h3>
            <div class="alert alert-danger">
                <strong>' . __d('sections', 'Etes-vous sûr de vouloir effacer cette Rubrique ?') . '</strong><br /><br />
                <a class="btn btn-danger btn-sm" href="admin.php?op=rubriquedelete&amp;rubid=' . $rubid . '&amp;ok=1" role="button">' . __d('sections', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" href="admin.php?op=sections" role="button">' . __d('sections', 'Non') . '</a>
            </div>';
    
            Css::adminfoot('', '', '', '');
        }
    }

}
