<?php

namespace App\Modules\Groupes\Controllers\Admin\Groupes;

use App\Modules\Npds\Core\AdminController;


class GroupesMaj extends AdminController
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
     * @param [type] $sub_op
     * @return void
     */
    public function groupe_maj($sub_op)
    {
        global $groupe_id, $groupe_name, $groupe_description;
    
        if ($sub_op == __d('groupes', 'Sauver les modifications')) {
            sql_query("UPDATE groupes SET groupe_name='$groupe_name', groupe_description='$groupe_description' WHERE groupe_id='$groupe_id'");
            
            global $aid;
            Ecr_Log("security", "UpdateGroup($groupe_id) by AID : $aid", '');
        }
    
        if ($sub_op == __d('groupes', 'Supprimer')) {
            $result = sql_query("SELECT uid, groupe FROM users_status WHERE groupe!='' ORDER BY uid ASC");
            
            $maj_ok = true;
            
            while (list($to_userid, $groupeX) = sql_fetch_row($result)) {
                $tab_groupe = explode(',', $groupeX);
                
                if ($tab_groupe) {
                    foreach ($tab_groupe as $groupevalue) {
                        if ($groupevalue == $groupe_id) {
                            $maj_ok = false;
                            break;
                        }
                    }
                }
            }
    
            if ($maj_ok)
                groupe_delete($groupe_id);
        }
    
        Header("Location: admin.php?op=groupes");
    }

}
