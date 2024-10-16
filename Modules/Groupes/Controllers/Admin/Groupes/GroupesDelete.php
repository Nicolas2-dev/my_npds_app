<?php

namespace Modules\Groupes\Controllers\Admin\Groupes;

use Modules\Npds\Core\AdminController;


class GroupesDelete extends AdminController
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
    public function groupe_delete($groupe_id)
    {
        sql_query("DELETE FROM lblocks WHERE member='$groupe_id'");
        sql_query("DELETE FROM rblocks WHERE member='$groupe_id'");
        sql_query("DELETE FROM groupes WHERE groupe_id='$groupe_id'");
        sql_query("DELETE FROM blocnotes WHERE bnid='" . md5("WS-BN" . $groupe_id) . "'");
    
        forum_groupe_delete($groupe_id);
        workspace_archive($groupe_id);
        groupe_mns_delete($groupe_id);
    
        global $aid;
        Ecr_Log('security', "DeleteGroup($groupe_id) by AID : $aid", '');
    }

}
