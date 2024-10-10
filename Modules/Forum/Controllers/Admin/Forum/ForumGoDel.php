<?php

namespace Modules\Forum\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class ForumGoDel extends AdminController
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
     * @param [type] $forum_id
     * @param integer $ok
     * @return void
     */
    function ForumGoDel($forum_id, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM forumtopics WHERE forum_id='$forum_id'");
            sql_query("DELETE FROM forum_read WHERE forum_id='$forum_id'");
    
            control_efface_post('forum_App', '', '', $forum_id);
    
            sql_query("DELETE FROM forums WHERE forum_id='$forum_id'");
            sql_query("DELETE FROM posts WHERE forum_id='$forum_id'");
    
            Q_Clean();
    
            global $aid;
            Ecr_Log('security', "DeleteForum($forum_id) by AID : $aid", '');
    
            Header("Location: admin.php?op=ForumAdmin");
        } else {
            echo '
            <hr />
            <div class="alert alert-danger">
                <p>' . __d('forum', 'ATTENTION :  êtes-vous certain de vouloir effacer ce Forum et tous ses Sujets ?') . '</p>
                <a class="btn btn-danger me-2" href="admin.php?op=ForumGoDel&amp;forum_id=' . $forum_id . '&amp;ok=1">' . __d('forum', 'Oui') . '</a>
                <a class="btn btn-secondary" href="admin.php?op=ForumAdmin" >' . __d('forum', 'Non') . '</a>
            </div>';
    
            adminfoot('', '', '', '');
        }
    }

}
