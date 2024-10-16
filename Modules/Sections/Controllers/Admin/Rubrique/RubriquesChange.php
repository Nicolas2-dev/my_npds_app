<?php

namespace Modules\Sections\Controllers\Admin\Rubrique;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class RubriquesChange extends AdminController
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
     * @param [type] $rubid
     * @param [type] $rubname
     * @param [type] $introc
     * @param [type] $enligne
     * @return void
     */
    public function rubriquechange($rubid, $rubname, $introc, $enligne)
    {
        $rubname    = stripslashes(Sanitize::FixQuotes($rubname));
        $introc     = dataimagetofileurl($introc, 'modules/upload/upload/rub');
        $introc     = stripslashes(Sanitize::FixQuotes($introc));
    
        sql_query("UPDATE rubriques SET rubname='$rubname', intro='$introc', enligne='$enligne' WHERE rubid='$rubid'");
    
        global $aid;
        Ecr_Log("security", "UpdateRubriques($rubid, $rubname) by AID : $aid", "");
    
        Header("Location: admin.php?op=sections");
    }

}
