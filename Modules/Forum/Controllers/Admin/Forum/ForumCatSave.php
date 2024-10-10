<?php

namespace Modules\Forum\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class ForumCatSave extends AdminController
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
     * @param [type] $old_catid
     * @param [type] $cat_id
     * @param [type] $cat_title
     * @return void
     */
    function ForumCatSave($old_catid, $cat_id, $cat_title)
    {
        $return = sql_query("UPDATE catagories SET cat_id='$cat_id', cat_title='" . AddSlashes($cat_title) . "' WHERE cat_id='$old_catid'");
    
        if ($return)
            sql_query("UPDATE forums SET cat_id='$cat_id' WHERE cat_id='$old_catid'");
    
        Q_Clean();
    
        global $aid;
        Ecr_Log("security", "UpdateForumCat($old_catid, $cat_id, $cat_title) by AID : $aid", '');
    
        Header("Location: admin.php?op=ForumAdmin");
    }

}
