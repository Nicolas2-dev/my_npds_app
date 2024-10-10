<?php

namespace Modules\Groupes\Controllers\Admin\Forum;

use Modules\Npds\Core\AdminController;


class GroupesForumCreate extends AdminController
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
     * @param [type] $groupe_name
     * @param [type] $description
     * @param [type] $moder
     * @return void
     */
    public function forum_groupe_create($groupe_id, $groupe_name, $description, $moder)
    {
        // creation forum
        // creation catégorie forum_groupe
        $result = sql_query("SELECT cat_id FROM catagories WHERE cat_id = -1;");
        list($cat_id) = sql_fetch_row($result);
    
        if (!$cat_id)
            sql_query("INSERT INTO catagories VALUES (-1, '" . __d('groupes', 'Groupe de travail') . "')");
        //==>creation forum
    
        echo "$groupe_id,$groupe_name,$description,$moder";
    
        sql_query("INSERT INTO forums VALUES (NULL, '$groupe_name', '$description', '1', '$moder', '-1', '7', '$groupe_id', '0', '0', '0')");
        
        //=> ajout etat forum (1 ou 0) dans le groupe
        sql_query("UPDATE groupes SET groupe_forum = '1' WHERE groupe_id = '$groupe_id';");
    
        global $aid;
        Ecr_Log("security", "CreateForumWS($groupe_id) by AID : $aid", '');
    }

}
