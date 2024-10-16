<?php

namespace Modules\Sections\Controllers\Admin\Section;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class SectionsChange extends AdminController
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
     * @param [type] $secid
     * @param [type] $secname
     * @param [type] $image
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $rubref
     * @param [type] $introd
     * @return void
     */
    public function sectionchange($secid, $secname, $image, $members, $Mmembers, $rubref, $introd)
    {
        if (is_array($Mmembers) and ($members == 1)) {
            $members = implode(',', $Mmembers);
    
            if ($members == 0) {
                $members = 1;
            }
        }
    
        $secname    = stripslashes(Sanitize::FixQuotes($secname));
        $image      = stripslashes(Sanitize::FixQuotes($image));
        $introd     = stripslashes(Sanitize::FixQuotes(dataimagetofileurl($introd, 'modules/upload/upload/sec')));
    
        sql_query("UPDATE sections SET secname='$secname', image='$image', userlevel='$members', rubid='$rubref', intro='$introd' WHERE secid='$secid'");
    
        global $aid;
        Ecr_Log('security', "UpdateSections($secid, $secname) by AID : $aid", '');
    
        Header("Location: admin.php?op=sections");
    }

}
