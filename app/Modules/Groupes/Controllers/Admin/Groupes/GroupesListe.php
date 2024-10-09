<?php

namespace App\Modules\Groupes\Controllers\Admin\Groupes;

use App\Modules\Npds\Core\AdminController;


class GroupesListe extends AdminController
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
    public function group_liste()
    {
        if ($al) {
            if (preg_match('#^mod#', $al)) {
                $al = explode('_', $al);
                $mes = __d('groupes', 'Vous ne pouvez pas exclure') . ' ' . $al[1] . ' ' . __d('groupes', 'car il est modérateur unique de forum. Oter ses droits de modération puis retirer le du groupe.');
            }
        }

        $result = sql_query("SELECT uid, groupe FROM users_status WHERE groupe!='' ORDER BY uid ASC");
    
        $one_gp = false;
    
        $tab_groupeII = array();
        $tab_groupeIII = array();
    
        $r = sql_query("SELECT groupe_id FROM groupes ORDER BY groupe_id ASC");
    
        while ($gl = sql_fetch_assoc($r)) {
            $tab_groupeII[$gl['groupe_id']] = '';
        }
    
        while (list($uid, $groupe) = sql_fetch_row($result)) {
            $one_gp = true;
            $tab_groupe = explode(',', $groupe);
    
            if ($tab_groupe) {
                foreach ($tab_groupe as $groupevalue) {
    
                    if ($groupevalue != '') {
                        $tab_groupeII[$groupevalue] .= $uid . ' ';
                        $tab_groupeIII[$groupevalue] = $groupevalue;
                    }
                }
            }
        }

        echo '<script type="text/javascript">
        //<![CDATA[';
    
        if ($al) 
            echo 'bootbox.alert("' . $mes . '")';
    
        echo '
        tog(\'lst_gr\',\'show_lst_gr\',\'hide_lst_gr\');
    
        //==> choix moderateur
        function choisir_mod_forum(gp,gn,ar_user,ar_uid) {
            var user_json = ar_user.split(",");
            var uid_json = ar_uid.split(",");
            var choix_mod = prompt("' . html_entity_decode(__d('groupes', 'Choisir un modérateur'), ENT_COMPAT | ENT_HTML401, cur_charset) . ' : \n"+user_json);
            if (choix_mod) {
                for (i=0; i<user_json.length; i++) {
                    if (user_json[i] == choix_mod) {var ind_uid=i;}
                }
                var xhr_object = null;
                if (window.XMLHttpRequest) // FF
                    xhr_object = new XMLHttpRequest();
                else if(window.ActiveXObject) // IE
                    xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
                xhr_object.open("GET", "admin.php?op=forum_groupe_create&groupe_id="+gp+"&groupe_name="+gn+"&moder="+uid_json[ind_uid], false);
                xhr_object.send(null);
                document.location.href="admin.php?op=groupes";
            }
        } 
        //<== choix moderateur
    
        //==> confirmation suppression tous les membres du groupe (done in xhr)
        function delete_AllMembersGroup(grp,ugp) {
            var xhr_object = null;
            if (window.XMLHttpRequest) // FF
                xhr_object = new XMLHttpRequest();
            else if(window.ActiveXObject) // IE
                xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
            if (confirm("' . __d('groupes', 'Vous allez exclure TOUS les membres du groupe') . ' "+grp+" !")) {
                xhr_object.open("GET", location.href="admin.php?op=retiredugroupe_all&groupe_id="+grp+"&tab_groupe="+ugp, false);
            }
        }
        //<== confirmation suppression tous les membres du groupe (done in xhr)
    
        //==> confirmation suppression groupe (done in xhr)
        function confirm_deleteGroup(gr) {
            var xhr_object = null;
            if (window.XMLHttpRequest) // FF
                xhr_object = new XMLHttpRequest();
            else if(window.ActiveXObject) // IE
                xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
            if (confirm("' . __d('groupes', 'Vous allez supprimer le groupe') . ' "+gr)) {
                xhr_object.open("GET", location.href="admin.php?op=groupe_maj&groupe_id="+gr+"&sub_op=' . __d('groupes', 'Supprimer') . '", false);
            }
        }
        //<== confirmation suppression groupe (done in xhr)
        //]]>
        </script>';
    
        echo '
        <hr />
        <form action="admin.php" method="post" name="nouveaugroupe">
            <input type="hidden" name="op" value="groupe_add" />
            <a href="#" onclick="document.forms[\'nouveaugroupe\'].submit()" title="' . __d('groupes', 'Ajouter un groupe') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fas fa-users fa-2x"></i><i class="fa fa-plus fa-lg me-1"></i></a> 
        </form>
        <hr />
        <h3 class="my-3"><a class="tog small" id="hide_lst_gr" title="' . __d('groupes', 'Replier la liste') . '" ><i id="i_lst_gr" class="fa fa-caret-up fa-lg text-primary" ></i></a>&nbsp;' . __d('groupes', 'Liste des groupes') . '</h3>
        <div id="lst_gr" class="row">
            <div id="gr_dat" class="p-3">';
    
        $lst_gr_json = '';
    
        if ($one_gp) {
            sort($tab_groupeIII);
    
            foreach ($tab_groupeIII as $bidon => $gp) {
                $lst_user_json = '';
    
                $result = sql_fetch_assoc(sql_query("SELECT groupe_id, groupe_name, groupe_description, groupe_forum, groupe_mns, groupe_chat, groupe_blocnote, groupe_pad FROM groupes WHERE groupe_id='$gp'"));
                
                echo '
                <div id="bloc_gr_' . $gp . '" class="row border rounded ms-1 p-2 px-0 mb-2 w-100">
                    <div class="col-lg-4 ">
                    <span>' . $gp . '</span>
                    <i class="fa fa-users fa-2x text-muted"></i><h4 class="my-2">' . aff_langue($result['groupe_name']) . '</h4><p>' . aff_langue($result['groupe_description']);
                
               if (file_exists('storage/users_private/groupe/' . $gp . '/groupe.png'))
                    echo '<img class="d-block my-2" src="storage/users_private/groupe/' . $gp . '/groupe.png" width="80" height="80" alt="logo_groupe" />';
                
                echo '
                </div>
                <div class="col-lg-5">';
    
                $tab_groupe = explode(' ', ltrim($tab_groupeII[$gp]));
                $nb_mb = (count($tab_groupe)) - 1;
    
                echo '
                   <a class="tog" id="show_lst_mb_' . $gp . '" title="' . __d('groupes', 'Déplier la liste') . '"><i id="i_lst_mb_gr_' . $gp . '" class="fa fa-caret-down fa-lg text-primary" ></i></a>&nbsp;&nbsp;
                   <i class="fa fa-user fa-2x text-muted"></i> <span class=" align-top badge bg-secondary">&nbsp;' . $nb_mb . '</span>&nbsp;&nbsp;';
                
               $lst_uid_json = '';
                $lst_uidna_json = '';
    
                //==> liste membres du groupe
                echo '<ul id="lst_mb_gr_' . $gp . '" style ="display:none; padding-left:0px; -webkit-padding-start: 0px;">';
    
                foreach ($tab_groupe as $bidon => $uidX) {
                    if ($uidX) {
    
                        list($uname, $user_avatar) = sql_fetch_row(sql_query("SELECT uname, user_avatar FROM users WHERE uid='$uidX'"));
                        
                        $lst_user_json .= $uname . ',';
                        $lst_uid_json .= $uidX . ',';
                        $lst_gr_json .= '\'mbgr_' . $gp . '\': { gp: \'' . $gp . '\'},';
    
                        if (!$user_avatar) {
                            $imgtmp = "assets/images/forum/avatar/blank.gif";
                        } else if (stristr($user_avatar, "users_private")) {
                            $imgtmp = $user_avatar;
                        } else {
                            if ($ibid = theme_image("forum/avatar/$user_avatar")) {
                                $imgtmp = $ibid;
                            } else {
                                $imgtmp = "assets/images/forum/avatar/$user_avatar";
                            }
    
                            if (!file_exists($imgtmp)) {
                                $imgtmp = "assets/images/forum/avatar/blank.gif";
                            }
                        }
    
                        echo '
                        <li id="' . $uname . $uidX . '_' . $gp . '" style="list-style-type:none;">
                            <div style="float:left;">
                                <a class="adm_tooltip"><em style="width:90px;"><img src="' . $imgtmp . '"  height="80" width="80" alt="avatar"/></em>- </a>
                            </div>
                            <div class="text-truncate" style="min-width:110px; width:110px; float:left;">
                                ' . $uname . '
                            </div>
                            <div>
                                <a href="admin.php?chng_uid=' . $uidX . '&amp;op=modifyUser" title="' . __d('groupes', 'Editer les informations concernant') . ' ' . $uname . '" data-bs-toggle="tooltip"><i class="fa fa-edit fa-lg fa-fw me-1"></i></a>
                                <a href="admin.php?op=retiredugroupe&amp;uid=' . $uidX . '&amp;uname=' . $uname . '&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Exclure') . ' ' . $uname . ' ' . __d('groupes', 'du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"><i class="fa fa-user-times fa-lg fa-fw text-danger me-1"></i></a>
                                <a href="" data-bs-toggle="collapse" data-bs-target="#moderation_' . $uidX . '_' . $gp . '" ><i class="fa fa-balance-scale fa-lg fa-fw" title="' . __d('groupes', 'Modérateur') . '" data-bs-toggle="tooltip"></i></a>
                                <div id="moderation_' . $uidX . '_' . $gp . '" class="collapse">';
    
                        //=>traitement moderateur
                        if ($result['groupe_forum'] == 1) {
                            $pat = '#\b' . $uidX . '\b#';
                            
                            $res = sql_query("SELECT f.forum_id, f.forum_name, f.forum_moderator FROM forums f WHERE f.forum_pass='$gp'");
                            
                            while ($row = sql_fetch_row($res)) {
                                $ar_moder = explode(',', $row[2]);
                                $tmp_moder = $ar_moder;
    
                                if (preg_match($pat, $row[2])) {
                                    unset($tmp_moder[array_search($uidX, $tmp_moder)]);
    
                                    $new_moder = implode(',', $tmp_moder);
    
                                    echo count($tmp_moder) != 0 ?
                                        '<a href="admin.php?op=moderateur_update&amp;forum_id=' . $row[0] . '&amp;forum_moderator=' . $new_moder . '" title="' . __d('groupes', 'Oter') . ' ' . $uname . ' ' . __d('groupes', 'des modérateurs du forum') . ' ' . $row[0] . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-balance-scale fa-lg fa-fw text-danger me-1"></i></a>' :
                                        '<i class="fa fa-balance-scale fa-lg fa-fw me-1" title="' . __d('groupes', 'Ce modérateur') . " (" . $uname . ") " . __d('groupes', 'n\'est pas modifiable tant qu\'un autre n\'est pas nommé pour ce forum') . ' ' . $row[0] . '" data-bs-toggle="tooltip" data-bs-placement="right" ></i>';
                                } else {
                                    $tmp_moder[] = $uidX;
                                    asort($tmp_moder);
    
                                    $new_moder = implode(',', $tmp_moder);
                                    echo '<a href="admin.php?op=moderateur_update&amp;forum_id=' . $row[0] . '&amp;forum_moderator=' . $new_moder . '" title="' . __d('groupes', 'Nommer') . ' ' . $uname . ' ' . __d('groupes', 'comme modérateur du forum') . ' ' . $row[1] . ' (' . $row[0] . ')" data-bs-toggle="tooltip" data-bs-placement="right" ><i class="fa fa-balance-scale fa-lg fa-fw me-1"></i></a>';
                                }
                            }
                        }
    
                        echo '
                            </div>
                        </div>
                        </li>';
                    }
                }
    
                echo '
                </ul>';
    
                $lst_user_json = rtrim($lst_user_json, ',');
                $lst_uid_json = rtrim($lst_uid_json, ',');
    
                //==> pliage repliage listes membres groupes
                echo '
                <script type="text/javascript">
                    //<![CDATA[
                    tog(\'lst_mb_gr_' . $gp . '\',\'show_lst_mb_' . $gp . '\',\'hide_lst_mb_' . $gp . '\');
                    //]]>
                </script>
                <i class="fa fa-user-times fa-lg text-danger" title="' . __d('groupes', 'Exclure TOUS les membres du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip" data-bs-placement="right" onclick="delete_AllMembersGroup(\'' . $gp . '\',\'' . $lst_uid_json . '\');"></i>';
                //<== liste membres du groupe
    
                //==> menu groupe
                echo '
                </div>
                <div class="col-lg-3 list-group-item px-0 mt-2 mt-md-0">
                    <a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_edit&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Editer groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fas fa-pencil-alt fa-lg"></i></a><a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="javascript:void(0);" onclick="bootbox.alert(\'' . __d('groupes', 'Avant de supprimer le groupe') . ' ' . $gp . ' ' . __d('groupes', 'vous devez supprimer TOUS ses membres !') . '\');" title="' . __d('groupes', 'Supprimer groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fas fa-trash fa-lg fa-fw"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=membre_add&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Ajouter un ou des membres au groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-user-plus fa-lg fa-fw"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=bloc_groupe_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Créer le bloc WS') . ' (' . $gp . ')" data-bs-toggle="tooltip"  ><i class="fa fa-clone fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
                
                echo $result['groupe_pad'] == 1 ?
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=pad_remove&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Désactiver PAD du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-edit fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>' :
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=pad_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Activer PAD du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-edit fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
               
                echo $result['groupe_blocnote'] == 1 ?
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=note_remove&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Désactiver bloc-note du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-sticky-note fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>' :
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=note_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Activer bloc-note du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-sticky-note fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
                
                echo file_exists('modules/f-manager/users/groupe_' . $gp . '.conf.php') ?
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=workspace_archive&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Désactiver gestionnaire de fichiers du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-folder fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>' :
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=workspace_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Activer gestionnaire de fichiers du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-folder fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
                
                echo $result['groupe_forum'] == 1 ?
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=forum_groupe_delete&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Supprimer forum du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-list-alt fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>' :
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="javascript:void(0);" onclick="javascript:choisir_mod_forum(\'' . $gp . '\',\'' . $result['groupe_name'] . '\',\'' . $lst_user_json . '\',\'' . $lst_uid_json . '\');" title="' . __d('groupes', 'Créer forum du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-list-alt fa-lg fa-fw"></i> <i class="fa fa-plus"></i></a>';
                
                echo $result['groupe_mns'] == 1 ?
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_mns_delete&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Supprimer MiniSite du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-desktop fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>' :
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_mns_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Créer MiniSite du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fa fa-desktop fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>';
                
                echo $result['groupe_chat'] == 0 ?
                    '<a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_chat_create&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Activer chat du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-comments fa-lg fa-fw"></i><i class="fa fa-plus"></i></a>' :
                    '<a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_chat_delete&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Désactiver chat du groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="far fa-comments fa-lg fa-fw"></i><i class="fa fa-minus"></i></a>';
                
                echo '
                    </div>
                </div>';
                //<== menu groupe
            }
        }
    
        // groupes sans membre
        $result = sql_query("SELECT groupe_id, groupe_name, groupe_description FROM groupes ORDER BY groupe_id ASC");
    
        while (list($gp, $gp_name, $gp_description) = sql_fetch_row($result)) {
            $gpA = true;
    
            if ($tab_groupeIII) {
                foreach ($tab_groupeIII as $bidon => $gpU) {
                    if ($gp == $gpU) $gpA = false;
                }
            }
    
            if ($gpA) {
                $lst_gr_json .= '\'mbgr_' . $gp . '\': { gp: \'' . $gp . '\'},';
    
                echo '
                <div class="row border rounded ms-1 p-2 px-0 mb-2 w-100">
                    <div id="bloc_gr_' . $gp . '" class="col-lg-5">
                    <span class="text-danger">' . $gp . '</span>
                    <i class="fa fa-users fa-2x text-muted"></i>
                    <h4 class="my-2 text-muted">' . aff_langue($gp_name) . '</h4>
                    <p class="text-muted">' . aff_langue($gp_description);
    
                if (file_exists('storage/users_private/groupe/' . $gp . '/groupe.png'))
                    echo '<img class="d-block my-2" src="storage/users_private/groupe/' . $gp . '/groupe.png" width="80" height="80" />';
    
                echo '
                    </p>
                    </div>
                    <div class="col-lg-4 ">
                    <i class="fa fa-user-o fa-2x text-muted"></i><span class="align-top badge bg-secondary ms-1">0</span>
                    </div>
                    <div class="col-lg-3 list-group-item px-0 mt-2">
                    <a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=groupe_edit&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Editer groupe') . ' ' . $gp . '" data-bs-toggle="tooltip"  ><i class="fas fa-pencil-alt fa-lg"></i></a><a class="btn btn-outline-danger btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="#" onclick="confirm_deleteGroup(\'' . $gp . '\');" title="' . __d('groupes', 'Supprimer groupe') . ' ' . $gp . '" data-bs-toggle="tooltip" ><i class="fas fa-trash fa-lg"></i></a><a class="btn btn-outline-secondary btn-sm col-lg-6 col-md-1 col-sm-2 col-3 mb-1 border-0" href="admin.php?op=membre_add&amp;groupe_id=' . $gp . '" title="' . __d('groupes', 'Ajouter un ou des membres au groupe') . ' ' . $gp . '" data-bs-toggle="tooltip" ><i class="fa fa-user-plus fa-lg"></i></a>
                    </div>
                </div>';
            }
        }
    
        $lst_gr_json = rtrim($lst_gr_json, ',');
    
        echo '
            </div>
        </div>';
    
        adminfoot('', '', '', '');
    }

}
