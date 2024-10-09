<?php

namespace App\Modules\Groupes\Controllers\Admin\Membre;

use App\Modules\Npds\Core\AdminController;


class GroupesMembreRetireGroupe extends AdminController
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
     * @param [type] $uid
     * @param [type] $uname
     * @return void
     */
    public function retiredugroupe($groupe_id, $uid, $uname)
    {
        $image = '18.png';
    
        $r = sql_query("SELECT groupe_name FROM groupes WHERE groupe_id='" . $groupe_id . "'");
        list($gn) = sql_fetch_row($r);
    
        $pat = '#^\b' . $uid . '\b$#';
    
        $mes_sys = '';
        $q = '';
        $ok = 0;
    
        $res = sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM forums f WHERE f.forum_pass='$groupe_id' AND cat_id='-1'");
        
        while ($row = sql_fetch_row($res)) {
            if (preg_match($pat, $row[2])) {
                $mes_sys = 'mod_' . $uname;
                $q = '&al=' . $mes_sys;
                $ok = 1;
            }
        }
    
        if ($ok == 0) {
            $pat = '#\b' . $uid . '\b#';
            
            $res = sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM forums f WHERE f.forum_pass='$groupe_id' AND cat_id='-1'");
            
            while ($r = sql_fetch_row($res)) {
                $new_moder = preg_replace('#,,#', ',', trim(preg_replace($pat, '', $r[2]), ','));
                
                sql_query("UPDATE forums SET forum_moderator='$new_moder' WHERE forum_id='$r[0]'");
            };
    
            $resultat = sql_query("SELECT groupe FROM users_status WHERE uid='$uid'");
    
            $subject = __d('groupes', 'Nouvelles du groupe') . ' ' . $gn;
            $message = __d('groupes', 'Vous ne faites plus partie des membres du groupe') . ' : ' . $gn . ' [' . $groupe_id . '].';
    
            $copie = '';
            $from_userid = 1;
            $to_userid = $uname;
    
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
    
            $resultat = sql_query("UPDATE users_status SET groupe='$groupesmodif' WHERE uid='$uid'");
            writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie);
    
            global $aid;
            Ecr_Log('security', "DeleteMemberToGroup($groupe_id, $uname) by AID : $aid", '');
        }
    
        Header("Location: admin.php?op=groupes" . $q);
    }

}
