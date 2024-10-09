<?php

namespace App\Modules\Groupes\Controllers\Admin\Workspace;

use App\Modules\Npds\Core\AdminController;


class GroupesWorkspaceArchive extends AdminController
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
    public function workspace_archive($groupe_id)
    {
        //=> archivage espace groupe
        $fp = fopen('storage/users_private/groupe/' . $groupe_id . '/delete', 'w');
        fclose($fp);
    
        //suppression fichier conf
        @unlink('modules/f-manager/users/groupe_' . $groupe_id . '.conf.php');
    
        global $aid;
        Ecr_Log('security', "ArchiveWS($groupe_id) by AID : $aid", '');
    }

}
