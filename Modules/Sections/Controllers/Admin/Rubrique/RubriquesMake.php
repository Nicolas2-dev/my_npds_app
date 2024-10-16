<?php

namespace Modules\Sections\Controllers\Admin\Rubrique;

use Modules\Npds\Core\AdminController;
use Modules\Sections\Support\Facades\Section;


class RubriquesMake extends AdminController
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
    protected $hlpfile = "";

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
    protected $f_meta_nom = '';


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
        $this->f_titre = __d('', '');

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
     * @param [type] $rubname
     * @param [type] $introc
     * @return void
     */
    public function rubriquemake($rubname, $introc)
    {
        global $radminsuper, $aid;
    
        $rubname    = stripslashes(Sanitize::FixQuotes($rubname));
        $introc     = stripslashes(Sanitize::FixQuotes(dataimagetofileurl($introc, 'modules/upload/upload/rub')));
    
        sql_query("INSERT INTO rubriques VALUES (NULL,'$rubname','$introc','0','0')");
    
        // mieux ? 
        // création automatique d'une sous rubrique avec droits ... ?
        if ($radminsuper != 1) {
            $result = sql_query("SELECT rubid FROM rubriques ORDER BY rubid DESC LIMIT 1");
            list($rublast) = sql_fetch_row($result);
    
            sql_query("INSERT INTO sections VALUES (NULL,'A modifier !', '', '', '$rublast', '<p>Cette sous-rubrique a été créé automatiquement. <br />Vous pouvez la personaliser et ensuite rattacher les publications que vous souhaitez.</p>','99','0')");
            
            $result = sql_query("SELECT secid FROM sections ORDER BY secid DESC LIMIT 1");
            list($seclast) = sql_fetch_row($result);
    
            Section::droitsalacreation($aid, $seclast);
    
            Ecr_Log('security', "CreateSections(Vide) by AID : $aid (via system)", '');
        }
        // mieux ... ?
    
        Ecr_Log('security', "CreateRubriques($rubname) by AID : $aid", '');
    
        Header("Location: admin.php?op=ordremodule");
    }

}
