<?php

namespace App\Modules\Forum\Controllers\Admin\Forum;

use App\Modules\Npds\Core\AdminController;


class ForumCatDel extends AdminController
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
    protected $hlpfile = 'forumcat';

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
    protected $f_meta_nom = 'ForumAdmin';


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
        $this->f_titre = __d('forum', 'Gestion des forums');

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
     * @param [type] $cat_id
     * @param integer $ok
     * @return void
     */
    function ForumCatDel($cat_id, $ok = 0)
    {
        if ($ok == 1) {
            $result = sql_query("SELECT forum_id FROM forums WHERE cat_id='$cat_id'");
    
            while (list($forum_id) = sql_fetch_row($result)) {
                sql_query("DELETE FROM forumtopics WHERE forum_id='$forum_id'");
                sql_query("DELETE FROM forum_read WHERE forum_id='$forum_id'");
    
                control_efface_post("forum_App", "", "", $forum_id);
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum_id'");
            }
    
            sql_query("DELETE FROM forums WHERE cat_id='$cat_id'");
            sql_query("DELETE FROM catagories WHERE cat_id='$cat_id'");
    
            Q_Clean();
    
            global $aid;
            Ecr_Log("security", "DeleteForumCat($cat_id) by AID : $aid", "");
    
            Header("Location: admin.php?op=ForumAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p>' . __d('forum', 'ATTENTION :  êtes-vous sûr de vouloir supprimer cette Catégorie, ses Forums et tous ses Sujets ?') . '</p>
                <a href="admin.php?op=ForumCatDel&amp;cat_id=' . $cat_id . '&amp;ok=1" class="btn btn-danger me-2">' . __d('forum', 'Oui') . '</a>
                <a href="admin.php?op=ForumAdmin" class="btn btn-secondary">' . __d('forum', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
