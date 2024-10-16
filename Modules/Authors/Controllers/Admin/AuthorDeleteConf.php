<?php

namespace Modules\Authors\Controllers\Admin;

use Modules\Authors\Models\Author;
use Modules\Npds\Core\AdminController;

class AuthorDeleteConf extends AdminController
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
    protected $hlpfile = "authors";

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
    protected $f_meta_nom = 'mod_authors';


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
        $this->f_titre = __d('authors', 'Administrateurs');

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
    public function deladminconf()
    {
        sql_query("DELETE FROM authors WHERE aid='$del_aid'");
        
        Author::deletedroits($chng_aid = $del_aid);

        sql_query("DELETE FROM publisujet WHERE aid='$del_aid'");

        // Supression du fichier pour filemanager
        @unlink("modules/f-manager/users/" . strtolower($del_aid) . ".conf.php");

        global $aid;
        Ecr_Log('security', "DeleteAuthor($del_aid) by AID : $aid", '');
        
        Header("Location: admin.php?op=mod_authors");
    }
    
}
