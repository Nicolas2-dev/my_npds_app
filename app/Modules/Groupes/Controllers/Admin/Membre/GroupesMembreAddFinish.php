<?php

namespace App\Modules\Groupes\Controllers\Admin\Membre;

use App\Modules\Npds\Core\AdminController;


class GroupesMembreAddFinish extends AdminController
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
     * @param [type] $luname
     * @return void
     */
    public function membre_add_finish($groupe_id, $luname)
    {
        $image = '18.png';
    
        $r = sql_query("SELECT groupe_name FROM groupes WHERE groupe_id='" . $groupe_id . "'");
        list($gn) = sql_fetch_row($r);
    
        $luname = rtrim($luname, ', ');
        $luname = str_replace(' ', '', $luname);
        $list_membres = explode(',', $luname);
        $nbremembres = count($list_membres);
    
        $subject = __d('groupes', 'Nouvelles du groupe') . ' ' . $gn;
        $message = __d('groupes', 'Vous faites désormais partie des membres du groupe') . ' : ' . $gn . ' [' . $groupe_id . '].';
    
        $copie = '';
        $from_userid = 1;
    
        for ($j = 0; $j < $nbremembres; $j++) {
            $uname = $list_membres[$j];
    
            $result1 = sql_query("SELECT uid FROM users WHERE uname='$uname'");
            $ibid = sql_fetch_assoc($result1);
    
            if ($ibid['uid']) {
                $to_userid = $uname;
                $result2 = sql_query("SELECT groupe FROM users_status WHERE uid='" . $ibid['uid'] . "'");
                $ibid2 = sql_fetch_assoc($result2);
    
                $lesgroupes = explode(',', $ibid2['groupe']);
                $nbregroupes = count($lesgroupes);
    
                $groupeexistedeja = false;
    
                for ($i = 0; $i < $nbregroupes; $i++) {
                    if ($lesgroupes[$i] == $groupe_id) {
                        $groupeexistedeja = true;
                        break;
                    }
                }
    
                if (!$groupeexistedeja) {
                    if ($ibid2['groupe']) 
                        $groupesmodif = $ibid2['groupe'] . ',' . $groupe_id;
                    else 
                        $groupesmodif = $groupe_id;
    
                    $resultat = sql_query("UPDATE users_status SET groupe='$groupesmodif' WHERE uid='" . $ibid['uid'] . "'");
                }
    
                writeDB_private_message($to_userid, $image, $subject, $from_userid, $message, $copie);
            }
        }
    
        global $aid;
    
        Ecr_Log('security', "AddMemberToGroup($groupe_id, $luname) by AID : $aid", '');
    
        Header("Location: admin.php?op=groupes");
    }
 
}
