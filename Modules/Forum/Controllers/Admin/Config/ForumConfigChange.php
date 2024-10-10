<?php

namespace Modules\Forum\Controllers\Admin\Config;

use Modules\Npds\Core\AdminController;


class ForumConfigChange extends AdminController
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
    protected $hlpfile = 'forumconfig';

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
    protected $f_meta_nom = 'ForumConfigAdmin';


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
        $this->f_titre = __d('forum', 'Configuration des Forums');

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
     * @param [type] $allow_html
     * @param [type] $allow_bbcode
     * @param [type] $allow_sig
     * @param [type] $posts_per_page
     * @param [type] $hot_threshold
     * @param [type] $topics_per_page
     * @param [type] $allow_upload_forum
     * @param [type] $allow_forum_hide
     * @param [type] $rank1
     * @param [type] $rank2
     * @param [type] $rank3
     * @param [type] $rank4
     * @param [type] $rank5
     * @param [type] $anti_flood
     * @param [type] $solved
     * @return void
     */
    public function ForumConfigChange($allow_html, $allow_bbcode, $allow_sig, $posts_per_page, $hot_threshold, $topics_per_page, $allow_upload_forum, $allow_forum_hide, $rank1, $rank2, $rank3, $rank4, $rank5, $anti_flood, $solved)
    {
        sql_query("UPDATE config SET allow_html='$allow_html', allow_bbcode='$allow_bbcode', allow_sig='$allow_sig', posts_per_page='$posts_per_page', hot_threshold='$hot_threshold', topics_per_page='$topics_per_page', allow_upload_forum='$allow_upload_forum', allow_forum_hide='$allow_forum_hide', rank1='$rank1', rank2='$rank2', rank3='$rank3', rank4='$rank4', rank5='$rank5', anti_flood='$anti_flood', solved='$solved'");
        
        Q_Clean();
        
        Header("Location: admin.php?op=ForumConfigAdmin");
    }

}
