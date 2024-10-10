<?php

namespace Modules\Groupes\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class GroupesForumDelete extends AdminController
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
    protected $hlpfile = 'groupes';

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
    protected $f_meta_nom = 'groupes';


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
        $this->f_titre = __d('groupes', 'Gestion des groupes');

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
     * @param [type] $groupe_id
     * @return void
     */
    public function forum_groupe_delete($groupe_id)
    {
        $result = sql_query("SELECT forum_id FROM forums WHERE forum_pass='$groupe_id' and cat_id='-1'");
        list($forum_id) = sql_fetch_row($result);
    
        // suppression des topics
        sql_query("DELETE FROM forumtopics WHERE forum_id='$forum_id'");
    
        // maj table lecture
        sql_query("DELETE FROM forum_read WHERE forum_id='$forum_id'");
    
        //=> suppression du forum
        sql_query("DELETE FROM forums WHERE forum_id='$forum_id'");
    
        // =>remise à 0 forum dans le groupe
        sql_query("UPDATE groupes SET groupe_forum = '0' WHERE groupe_id='$groupe_id'");
    
        global $aid;
        Ecr_Log('security', "DeleteForumWS($forum_id) by AID : $aid", '');
    }

}
