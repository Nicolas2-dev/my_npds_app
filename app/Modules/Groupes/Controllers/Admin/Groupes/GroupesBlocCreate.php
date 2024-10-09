<?php

namespace App\Modules\Groupes\Controllers\Admin\Groupes;

use App\Modules\Npds\Core\AdminController;


class GroupesChatBlocCreate extends AdminController
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
    protected $hlpfile = 'groupes';

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
    protected $f_meta_nom = 'groupes';


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
        $this->f_titre = __d('groupes', 'Gestion des groupes');

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
     * @param [type] $groupe_id
     * @return void
     */
    public function bloc_groupe_create($groupe_id)
    {
        // Creation bloc espace de travail user
        // On créer le bloc s'il n'existe pas déjà
        $bloc = false;
    
        $menu_workspace = "function#bloc_espace_groupe\r\nparams#$groupe_id,1";
    
        $row = sql_fetch_row(sql_query("SELECT COUNT(id) FROM lblocks WHERE content='$menu_workspace'"));
    
        if ($row[0] == 0) {
            $row = sql_fetch_row(sql_query("SELECT COUNT(id) FROM rblocks WHERE content='$menu_workspace'"));
    
            if ($row[0] <> 0)
                $bloc = true;
        } else
            $bloc = true;
    
        if ($bloc == false)
            sql_query("INSERT INTO lblocks VALUES (NULL, '', '$menu_workspace', '$groupe_id', '3', '0', '1', '0', NULL)");
    }

}
