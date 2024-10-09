<?php

namespace App\Modules\Groupes\Controllers\Admin\Minisite;

use App\Modules\Npds\Core\AdminController;


class GroupesMinisiteDelete extends AdminController
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
    public function groupe_mns_delete($groupe_id)
    {
        include("modules/upload/upload.conf.php");
    
        if ($DOCUMENTROOT == '') {
            global $DOCUMENT_ROOT;
            if ($DOCUMENT_ROOT) {
                $DOCUMENTROOT = $DOCUMENT_ROOT;
            } else {
                $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
            }
        }
    
        $user_dir = $DOCUMENTROOT . $racine . '/storage/users_private/groupe/' . $groupe_id;
    
        // Supprimer son ministe s'il existe
        if (is_dir($user_dir . '/mns')) {
            $dir = opendir($user_dir . '/mns');
    
            while (false !== ($nom = readdir($dir))) {
                if ($nom != '.' && $nom != '..' && $nom != '') {
                    @unlink($user_dir . '/mns/' . $nom);
                }
            }
    
            closedir($dir);
            @rmdir($user_dir . '/mns');
        }
    
        sql_query("UPDATE groupes SET groupe_mns = '0' WHERE groupe_id = '$groupe_id';");
    
        global $aid;
        Ecr_Log('security', "DeleteMnsWS($groupe_id) by AID : $aid", '');
    }

}

