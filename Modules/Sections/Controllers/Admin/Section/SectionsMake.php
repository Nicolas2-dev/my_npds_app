<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;
use Modules\Sections\Support\Facades\Section;


class SectionsMake extends AdminController
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
     * @param [type] $secname
     * @param [type] $image
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $rubref
     * @param [type] $introd
     * @return void
     */
    public function sectionmake($secname, $image, $members, $Mmembers, $rubref, $introd)
    {
        global $radminsuper, $aid;
    
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
    
            if ($members == 0) {
                $members = 1;
            }
        }
    
        $secname    = stripslashes(Sanitize::FixQuotes($secname));
        $rubref     = stripslashes(Sanitize::FixQuotes($rubref));
        $image      = stripslashes(Sanitize::FixQuotes($image));
    
        $introd = stripslashes(Sanitize::FixQuotes(dataimagetofileurl($introd, 'modules/upload/upload/sec')));
        
        sql_query("INSERT INTO sections VALUES (NULL,'$secname', '$image', '$members', '$rubref', '$introd','99','0')");
    
        if ($radminsuper != 1) {
            $result = sql_query("SELECT secid FROM sections ORDER BY secid DESC LIMIT 1");
            list($secid) = sql_fetch_row($result);
    
            Section::droitsalacreation($aid, $secid);
        }
    
        Ecr_Log('security', "CreateSections($secname) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }

}
