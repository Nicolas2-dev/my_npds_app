<?php

namespace Modules\Forum\Controllers\Admin\Maint;

use Modules\Npds\Core\AdminController;


class ForumMaintTopicDetail extends AdminController
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
     * @param [type] $topic
     * @param [type] $topic_title
     * @return void
     */
    public function ForumMaintTopicDetail($topic, $topic_title)
    {
        $resultTT = sql_query("SELECT post_text, post_time FROM posts WHERE topic_id='$topic' ORDER BY post_time DESC LIMIT 0,1");
        list($post_text, $post_time) = sql_fetch_row($resultTT);
    
        echo '
        <hr />
        <h3 class="mb-3 text-danger">' . __d('forum', 'Supprimer massivement les Topics') . '</h3>
        <div class="lead">Topic : ' . $topic . ' | ' . stripslashes($topic_title) . '</div>
        <div class="card p-4 my-3 border-danger">
            <p class="text-end small text-muted">[ ' . convertdate($post_time) . ' ]</p>' . stripslashes($post_text) . '
        </div>
        <form action="admin.php" method="post">
            <input type="hidden" name="op" value="ForumMaintTopicSup" />
            <input type="hidden" name="topic" value="' . $topic . '" />
            <input class="btn btn-danger" type="submit" name="Topics_Del" value="' . __d('forum', 'Effacer') . '" />
        </form>';
    
        sql_free_result($resultTT);
    
        adminfoot('', '', '', '');
    }

}
