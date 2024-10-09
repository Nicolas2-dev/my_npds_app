<?php

namespace App\Modules\Groupes\Controllers\Admin\Workspace;

use App\Modules\Npds\Core\AdminController;


class GroupesWorkspaceCreate extends AdminController
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
    public function workspace_create($groupe_id)
    {
        //==>creation fichier conf du groupe
        @copy('modules/f-manager/users/groupe.conf.php', 'modules/f-manager/users/groupe_' . $groupe_id . '.conf.php');
    
        $file = file('modules/f-manager/users/groupe_' . $groupe_id . '.conf.php');
        $file[29] = "   \$access_fma = \"$groupe_id\";\n";
        $fic = fopen('modules/f-manager/users/groupe_' . $groupe_id . '.conf.php', "w");
    
        foreach ($file as $n => $ligne) {
            fwrite($fic, $ligne);
        }
        fclose($fic);
    
        include("modules/upload/upload.conf.php");
    
        if ($DOCUMENTROOT == '') {
            global $DOCUMENT_ROOT;
            if ($DOCUMENT_ROOT)
                $DOCUMENTROOT = $DOCUMENT_ROOT;
            else
                $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
        }
    
        $user_dir = $DOCUMENTROOT . $racine . '/storage/users_private/groupe/' . $groupe_id;
    
        // DOCUMENTS_GROUPE
        @mkdir('storage/users_private/groupe/' . $groupe_id . '/documents_groupe');
    
        $repertoire = $user_dir . '/documents_groupe';
        $directory = $racine . '/modules/groupe/matrice/documents_groupe';
        $handle = opendir($DOCUMENTROOT . $directory);
    
        while (false !== ($file = readdir($handle))) 
            $filelist[] = $file;
    
        asort($filelist);
    
        foreach ($filelist as $key => $file) {
            if ($file <> '.' and $file <> '..')
                @copy($DOCUMENTROOT . $directory . '/' . $file, $repertoire . '/' . $file);
        }
    
        closedir($handle);
        unset($filelist);
    
        // IMAGES_GROUPE
        @mkdir('storage/users_private/groupe/' . $groupe_id . '/images_groupe');
    
        $repertoire = $user_dir . '/images_groupe';
        $directory = $racine . '/modules/groupe/matrice/images_groupe';
        $handle = opendir($DOCUMENTROOT . $directory);
    
        while (false !== ($file = readdir($handle))) 
            $filelist[] = $file;
    
        asort($filelist);
    
        foreach ($filelist as $key => $file) {
            if ($file <> '.' and $file <> '..')
                @copy($DOCUMENTROOT . $directory . '/' . $file, $repertoire . '/' . $file);
        }
    
        closedir($handle);
        unset($filelist);
    
        @unlink('storage/users_private/groupe/' . $groupe_id . '/delete');
    
        global $aid;
        Ecr_Log('security', "CreateWS($groupe_id) by AID : $aid", '');
    }

}
