<?php

namespace App\Modules\Forum\Controllers\Admin\Maint;

use App\Modules\Npds\Core\AdminController;


class ForumSynchroForum extends AdminController
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
    protected $hlpfile = 'forummaint';

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
    protected $f_meta_nom = 'MaintForumAdmin';


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
        $this->f_titre = __d('forum', 'Maintenance des Forums');

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
     * @return void
     */
    public function SynchroForum()
    {
        // affectation d'un topic à un forum
        if (!$result1 = sql_query("SELECT topic_id, forum_id FROM forumtopics ORDER BY topic_id ASC"))
            forumerror('0009');
    
        while (list($topi_cid, $foru_mid) = sql_fetch_row($result1)) {
            sql_query("UPDATE posts SET forum_id='$foru_mid' WHERE topic_id='$topi_cid' and forum_id>0");
        }
    
        sql_free_result($result1);
    
        // table forum_read et contenu des topic
        if (!$result1 = sql_query("SELECT topicid, uid, rid FROM forum_read ORDER BY topicid ASC"))
            forumerror('0009');
    
        while (list($topicid, $uid, $rid) = sql_fetch_row($result1)) {
            if (($topicid . $uid) == $tmp)
                $resultD = sql_query("DELETE FROM forum_read WHERE topicid='$topicid' and uid='$uid' and rid='$rid'");
    
            $tmp = $topicid . $uid;
    
            if ($result2 = sql_query("SELECT topic_id FROM forumtopics WHERE topic_id = '$topicid'")) {
                list($topic_id) = sql_fetch_row($result2);
    
                if (!$topic_id)
                    $result3 = sql_query("DELETE FROM forum_read WHERE topicid='$topicid'");
            }
    
            sql_free_result($result2);
        }
    
        sql_free_result($result1);
    
        header("location: admin.php?op=MaintForumAdmin");
    }

}
