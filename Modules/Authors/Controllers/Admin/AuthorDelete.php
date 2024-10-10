<?php

namespace Modules\Authors\Controllers\Admin;

use Modules\Npds\Core\AdminController;

class AuthorDelete extends AdminController
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
    public function deladmin()
    {
        echo '
        <hr />
        <h3>' . __d('authors', 'Effacer l\'Administrateur') . ' : <span class="text-muted">' . $del_aid . '</span></h3>
        <div class="alert alert-danger">
        <p><strong>' . __d('authors', 'Etes-vous sûr de vouloir effacer') . ' ' . $del_aid . ' ? </strong></p>
        <a href="admin.php?op=deladminconf&amp;del_aid=' . $del_aid . '" class="btn btn-danger btn-sm">' . __d('authors', 'Oui') . '</a>
        &nbsp;<a href="admin.php?op=mod_authors" class="btn btn-secondary btn-sm">' . __d('authors', 'Non') . '</a>
        </div>';

        adminfoot('', '', '', '');
    }
    
}
