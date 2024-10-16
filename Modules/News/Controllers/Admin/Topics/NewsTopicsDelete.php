<?php

namespace Modules\News\Controllers\Admin;


use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Language;


class NewsTopicsDelete extends AdminController
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
    protected $hlpfile = 'topics';

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
    protected $f_meta_nom = 'topicsmanager';


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
        $this->f_titre = __d('news', 'Gestion des sujets');

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
     * @param [type] $topicid
     * @param integer $ok
     * @return void
     */
    public function topicdelete($topicid, $ok = 0)
    {
        if ($ok == 1) {
    
            $result = sql_query("SELECT sid FROM stories WHERE topic='$topicid'");
            list($sid) = sql_fetch_row($result);
    
            sql_query("DELETE FROM stories WHERE topic='$topicid'");
            Ecr_Log("security", "topicDelete (stories, $topicid) by AID : $aid", "");
    
            sql_query("DELETE FROM topics WHERE topicid='$topicid'");
            Ecr_Log("security", "topicDelete (topic, $topicid) by AID : $aid", "");
    
            sql_query("DELETE FROM related WHERE tid='$topicid'");
            Ecr_Log("security", "topicDelete (related, $topicid) by AID : $aid", '');
    
            // commentaires
            if (file_exists("modules/comments/article.conf.php")) {
                include("modules/comments/article.conf.php");
    
                sql_query("DELETE FROM posts WHERE forum_id='$forum' and topic_id='$topic'");
                Ecr_Log("security", "topicDelete (comments, $topicid) by AID : $aid", "");
            }
    
            Header("Location: admin.php?op=topicsmanager");
        } else {
            $result2 = sql_query("SELECT topicimage, topicname, topictext FROM topics WHERE topicid='$topicid'");
            list($topicimage, $topicname, $topictext) = sql_fetch_row($result2);

            echo '<h3 class=""><span class="text-danger">' . __d('news', 'Effacer le Sujet') . ' : </span>' . Language::aff_langue($topicname) . '</h3>';
            echo '<div class="alert alert-danger lead" role="alert">';
    
            if ($topicimage != "")
                echo '
                <div class="thumbnail">
                    <img class="img-fluid" src="' . Config::get('npds.tipath') . $topicimage . '" alt="logo-topic" />
                </div>';
    
            echo '
                <p>' . __d('news', 'Etes-vous sûr de vouloir effacer ce sujet ?') . ' : ' . $topicname . '</p>
                <p>' . __d('news', 'Ceci effacera tous ses articles et ses commentaires !') . '</p>
                <p><a class="btn btn-danger" href="admin.php?op=topicdelete&amp;topicid=' . $topicid . '&amp;ok=1">' . __d('news', 'Oui') . '</a>&nbsp;<a class="btn btn-primary"href="admin.php?op=topicsmanager">' . __d('news', 'Non') . '</a></p>
            </div>';
    
            Css::adminfoot('', '', '', '');
        }
    }

}
