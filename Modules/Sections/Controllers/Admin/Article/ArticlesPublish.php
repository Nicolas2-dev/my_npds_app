<?php

namespace Modules\Sections\Controllers\Admin\Article;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Mailer;


class ArticlesPublish extends AdminController
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
     * @param [type] $author
     * @param [type] $members
     * @param [type] $Mmembers
     * @return void
     */
    public function secartpublish($artid, $secid, $title, $content, $author, $members, $Mmembers)
    {
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
        }
    
        $content    = stripslashes(Sanitize::FixQuotes(dataimagetofileurl($content, 'modules/upload/upload/s')));
        $title      = stripslashes(Sanitize::FixQuotes($title));
    
        if ($secid != '0') {
            sql_query("DELETE FROM seccont_tempo WHERE artid='$artid'");
            $timestamp = time();
    
            sql_query("INSERT INTO seccont VALUES (NULL,'$secid','$title','$content', '0', '$author', '99', '$members', '$timestamp')");
            
            global $aid;
            Ecr_Log('security', "PublicateArticleSections($artid, $secid, $title) by AID : $aid", '');
    
            $result = sql_query("SELECT email FROM authors WHERE aid='$author'");
            list($lemail) = sql_fetch_row($result);
    
            $sujet = html_entity_decode(__d('sections', 'Validation de votre publication'), ENT_COMPAT | ENT_HTML401, cur_charset);
            $message = __d('sections', 'La publication que vous aviez en attente vient d\'être validée');
    
            Mailer::send_email($lemail, $sujet, $message, Config::get('npds.notify_from'), true, "html", '');
        }
    
        Header("Location: admin.php?op=sections");
    }

}
