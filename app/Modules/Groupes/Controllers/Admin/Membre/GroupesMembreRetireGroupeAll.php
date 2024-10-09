<?php

namespace App\Modules\Groupes\Controllers\Admin\Membre;

use App\Modules\Npds\Core\AdminController;


class GroupesMembreRetireGroupeAll extends AdminController
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
     * @param [type] $tab_groupe
     * @return void
     */
    public function retiredugroupe_all($groupe_id, $tab_groupe)
    {
        $tab_groupe = explode(',', $tab_groupe);
    
        foreach ($tab_groupe as $bidon => $uidZ) {
            if ($uidZ) {
    
                // a rajouter enlever modérateur forum
                $resultat = sql_query("SELECT groupe FROM users_status WHERE uid='$uidZ'");
                $valeurs = sql_fetch_assoc($resultat);
    
                $lesgroupes = explode(',', $valeurs['groupe']);
                $nbregroupes = count($lesgroupes);
    
                $groupesmodif = '';
    
                for ($i = 0; $i < $nbregroupes; $i++) {
                    if ($lesgroupes[$i] != $groupe_id) {
                        if ($groupesmodif == '') 
                            $groupesmodif .= $lesgroupes[$i];
                        else 
                            $groupesmodif .= ',' . $lesgroupes[$i];
                    }
                }
    
                $resultat = sql_query("UPDATE users_status SET groupe='$groupesmodif' WHERE uid='$uidZ'");
    
                global $aid;
                Ecr_Log('security', "DeleteAllMemberToGroup($groupe_id, $uidZ) by AID : $aid", '');
            }
        }
    
        Header("Location: admin.php?op=groupes");
    }

}
