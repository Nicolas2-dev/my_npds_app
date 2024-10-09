<?php

namespace App\Modules\Forum\Controllers\Admin\Forum;

use App\Modules\Npds\Core\AdminController;


class ForumGoSave extends AdminController
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
    protected $hlpfile = 'forumcat';

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
    protected $f_meta_nom = 'ForumAdmin';


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
        $this->f_titre = __d('forum', 'Gestion des forums');

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
     * @param [type] $forum_id
     * @param [type] $forum_name
     * @param [type] $forum_desc
     * @param [type] $forum_access
     * @param [type] $forum_mod
     * @param [type] $cat_id
     * @param [type] $forum_type
     * @param [type] $forum_pass
     * @param [type] $arbre
     * @param [type] $attachement
     * @param [type] $forum_index
     * @param [type] $ctg
     * @return void
     */
    function ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass, $arbre, $attachement, $forum_index, $ctg)
    {
        // il faut supprimer le dernier , à cause de l'auto-complete
        $forum_mod = rtrim(chop($forum_mod), ',');
        $moderator = explode(',', $forum_mod);
    
        $forum_mod = '';
        $error_mod = '';
    
        for ($i = 0; $i < count($moderator); $i++) {
            $result = sql_query("SELECT uid FROM users WHERE uname='" . trim($moderator[$i]) . "'");
            list($forum_moderator) = sql_fetch_row($result);
    
            if ($forum_moderator != '') {
                $forum_mod .= $forum_moderator . ' ';
    
                sql_query("UPDATE users_status SET level='2' WHERE uid='$forum_moderator'");
            } else
                $error_mod .= $moderator[$i] . ' ';
        }
    
        if ($error_mod != '') {
            echo "<div><p align=\"center\">" . __d('forum', 'Le Modérateur sélectionné n\'existe pas.') . " : $error_mod<br />";
            echo "[ <a href=\"javascript:history.go(-1)\" >" . __d('forum', 'Retour en arrière') . "</a> ]</p></div>";
        } else {
            $forum_mod = str_replace(' ', ',', chop($forum_mod));
    
            if ($arbre > 1)
                $arbre = 1;
    
            if ($forum_pass) {
                if (($forum_type == 7) and ($forum_access == 0)) {
                    $forum_access = 1;
                }
    
                sql_query("UPDATE forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='$forum_pass', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
            } else
                sql_query("UPDATE forums SET forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_mod', cat_id='$cat_id', forum_type='$forum_type', forum_pass='', arbre='$arbre', attachement='$attachement', forum_index='$forum_index' WHERE forum_id='$forum_id'");
    
            Q_Clean();
            global $aid;
            Ecr_Log("security", "UpdateForum($forum_id, $forum_name) by AID : $aid", '');
    
            Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
        }
    }
    
}
