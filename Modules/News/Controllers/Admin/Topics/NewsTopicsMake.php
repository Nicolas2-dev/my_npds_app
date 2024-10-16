<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class NewsTopicsMake extends AdminController
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
    public function topicmake($topicname, $topicimage, $topictext, $topicadmin)
    {
        $topicname = stripslashes(Sanitize::FixQuotes($topicname));
    
        $istopicname = sql_num_rows(sql_query("SELECT * FROM topics WHERE topicname='$topicname'"));
    
        if ($istopicname !== 0) {
            Header("Location: admin.php?op=topicsmanager&nook=nook#addtopic");
            die();
        }
    
        $topicimage = stripslashes(Sanitize::FixQuotes($topicimage));
        $topictext  = stripslashes(Sanitize::FixQuotes($topictext));
    
        sql_query("INSERT INTO topics VALUES (NULL,'$topicname','$topicimage','$topictext','0', '$topicadmin')");
    
        global $aid;
        Ecr_Log("security", "topicMake ($topicname) by AID : $aid", "");
    
        $topicadminX = explode(",", $topicadmin);
        array_pop($topicadminX);
    
        for ($i = 0; $i < count($topicadminX); $i++) {
            trim($topicadminX[$i]);
    
            $nres = sql_num_rows(sql_query("SELECT * FROM droits WHERE d_aut_aid='$topicadminX[$i]' and d_droits=11112"));
    
            if ($nres == 0) {
                sql_query("INSERT INTO droits VALUES ('$topicadminX[$i]', '2', '11112')");
            }
        }
    
        Header("Location: admin.php?op=topicsmanager#addtopic");
    }

}
