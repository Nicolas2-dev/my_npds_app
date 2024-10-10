<?php

namespace Modules\Groupes\Controllers\Admin\Membre;

use Modules\Npds\Core\AdminController;


class GroupesMemberAsk extends AdminController
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
     * @return void
     */
    public function groupe_member_ask()
    {
        global $sub_op, $myrow, $groupe_asked, $user_asked;
    
        $directory = "storage/users_private/groupe";
    
        if (isset($sub_op)) {
    
            $res = sql_query("SELECT uname FROM users WHERE uid='" . $user_asked . "'");
            list($uname) = sql_fetch_row($res);
    
            $r = sql_query("SELECT groupe_name FROM groupes WHERE groupe_id='" . $groupe_asked . "'");
            list($gn) = sql_fetch_row($r);
    
            $subject = __d('groupes', 'Nouvelles du groupe') . ' ' . $gn;
            $image = '18.png';
    
            if ($sub_op == __d('groupes', 'Oui')) {
                $message = '✅ ' . __d('groupes', 'Demande acceptée.') . ' ' . __d('groupes', 'Vous faites désormais partie des membres du groupe') . ' : ' . $gn . ' [' . $groupe_asked . '].';
                
                unlink($directory . '/ask4group_' . $user_asked . '_' . $groupe_asked . '_.txt');
                
                $result2 = sql_query("SELECT groupe FROM users_status WHERE uid='" . $user_asked . "'");
                $ibid2 = sql_fetch_assoc($result2);
    
                $lesgroupes = explode(',', $ibid2['groupe']);
                $nbregroupes = count($lesgroupes);
    
                $groupeexistedeja = false;
    
                for ($i = 0; $i < $nbregroupes; $i++) {
                    if ($lesgroupes[$i] == $groupe_asked) {
                        $groupeexistedeja = true;
                        break;
                    }
                }
    
                if (!$groupeexistedeja) {
                    $groupesmodif = $ibid2['groupe'] ? $ibid2['groupe'] . ',' . $groupe_asked : $groupe_asked;
                    $resultat = sql_query("UPDATE users_status SET groupe='$groupesmodif' WHERE uid='" . $user_asked . "'");
                }
    
                writeDB_private_message($uname, $image, $subject, 1, $message, '');
    
                global $aid;
                Ecr_Log('security', "AddMemberToGroup($groupe_asked, $uname) by AID : $aid", '');
    
                Header("Location: admin.php?op=groupes");
            }
    
            if ($sub_op == __d('groupes', 'Non')) {
                $message = '🚫 ' . __d('groupes', 'Demande refusée pour votre participation au groupe') . ' : ' . $gn . ' [' . $groupe_asked . '].';
                
                unlink($directory . '/ask4group_' . $user_asked . '_' . $groupe_asked . '_.txt');
                writeDB_private_message($uname, $image, $subject, 1, $message, '');
    
                Header("Location: admin.php?op=groupes");
            }
        }

        $iterator = new DirectoryIterator($directory);
        $j = 0;
    
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() and strpos($fileinfo->getFilename(), 'ask4group') !== false) {
                $us_gr = explode('_', $fileinfo->getFilename());
                $myrow = get_userdata_from_id($us_gr[1]);
    
                $r = sql_query("SELECT groupe_name FROM groupes WHERE groupe_id='" . $us_gr[2] . "'");
                list($gn) = sql_fetch_row($r);
    
                echo '
                <form id="acceptmember_' . $us_gr[1] . '_' . $us_gr[2] . '" class="admform" action="admin.php" method="post">
                    <div id="" class="">
                    ' . __d('groupes', 'Accepter') . ' ' . $myrow['uname'] . ' ' . __d('groupes', 'dans le groupe') . ' ' . $us_gr[2] . ' : ' . $gn . ' ?
                    </div>
                    <input type="hidden" name="op" value="groupe_member_ask" />
                    <input type="hidden" name="user_asked" value="' . $us_gr[1] . '" />
                    <input type="hidden" name="groupe_asked" value="' . $us_gr[2] . '" />
                    <div class="mb-3">
                        <input class="btn btn-primary btn-sm" type="submit" name="sub_op" value="' . __d('groupes', 'Oui') . '" />
                        <input class="btn btn-primary btn-sm" type="submit" name="sub_op" value="' . __d('groupes', 'Non') . '" />
                    </div>
                </form>';
    
                $j++;
            }
        }
    }

}
