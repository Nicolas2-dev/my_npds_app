<?php

namespace Modules\Sections\Controllers\Admin\Article;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class ArticlesChange extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * @param [type] $artid
     * @param [type] $secid
     * @param [type] $title
     * @param [type] $content
     * @param [type] $members
     * @param [type] $Mmembers
     * @return void
     */
    public function secartchange($artid, $secid, $title, $content, $members, $Mmembers)
    {
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
        }
    
        $title      = stripslashes(Sanitize::FixQuotes($title));
        $content    = stripslashes(Sanitize::FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/s')));
        $timestamp  = time();
    
        if ($secid != '0') {
            sql_query("UPDATE seccont SET secid='$secid', title='$title', content='$content', userlevel='$members', timestamp='$timestamp' WHERE artid='$artid'");
            
            global $aid;
            Ecr_Log("security", "UpdateArticleSections($artid, $secid, $title) by AID : $aid", "");
        }
    
        Header("Location: admin.php?op=secartedit&artid=$artid");
    }

}
