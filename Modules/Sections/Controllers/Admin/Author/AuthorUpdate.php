<?php

namespace Modules\Sections\Controllers\Admin\Author;

use Modules\Npds\Core\AdminController;


class AuthorUpdate extends AdminController
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
     * @param [type] $chng_aid
     * @param [type] $maxindex
     * @param [type] $creation
     * @param [type] $publication
     * @param [type] $modification
     * @param [type] $suppression
     * @return void
     */
    public function updaterights($chng_aid, $maxindex, $creation, $publication, $modification, $suppression)
    {
        if ($radminsuper != 1) {
            Header("Location: admin.php?op=sections");
        }
    
        $result = sql_query("DELETE FROM publisujet WHERE aid='$chng_aid'");
    
        for ($j = 1; $j < ($maxindex + 1); $j++) {
            if (array_key_exists($j, $creation)) {
                if ($creation[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$creation[$j]','1')");
                }
            }
    
            if (array_key_exists($j, $publication)) {
                if ($publication[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$publication[$j]','2')");
                }
            }
    
            if (array_key_exists($j, $modification)) {
                if ($modification[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$modification[$j]','3')");
                }
            }
    
            if (array_key_exists($j, $suppression)) {
                if ($suppression[$j] != '') {
                    $result = sql_query("INSERT INTO publisujet VALUES ('$chng_aid','$suppression[$j]','4')");
                }
            }
        }
    
        global $aid;
        Ecr_Log('security', "UpdateRightsPubliSujet($chng_aid) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }

}
