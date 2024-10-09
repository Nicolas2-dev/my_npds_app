<?php

namespace App\Modules\Forum\Controllers\Admin\Maint;

use App\Modules\Npds\Core\AdminController;


class ForumMaintTopicMassiveSup extends AdminController
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
     * @param [type] $topics
     * @return void
     */
    public function ForumMaintTopicMassiveSup($topics)
    {
        if ($topics) {
            foreach ($topics as $topic_id => $value) {
                if ($value == 'on') {
    
                    $sql = "DELETE FROM posts WHERE topic_id = '$topic_id'";
    
                    if (!$result = sql_query($sql))
                        forumerror('0009');
    
                    $sql = "DELETE FROM forumtopics WHERE topic_id = '$topic_id'";
    
                    if (!$result = sql_query($sql))
                        forumerror('0010');
    
                    $sql = "DELETE FROM forum_read WHERE topicid = '$topic_id'";
    
                    if (!$r = sql_query($sql))
                        forumerror('0001');
    
                    control_efface_post("forum_App", "", $topic_id, "");
                }
            }
        }
    
        Q_Clean();
    
        header("location: admin.php?op=MaintForumAdmin");
    }

}
