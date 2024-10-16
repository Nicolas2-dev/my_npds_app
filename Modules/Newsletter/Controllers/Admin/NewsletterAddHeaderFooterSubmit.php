<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class Newsletter extends AdminController
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
    protected $hlpfile = 'lnl';

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
    protected $f_meta_nom = 'lnl';


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
        $this->f_titre = __d('newsletter', 'Petite Lettre D\'information');

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
     * case "Add_Footer_Submit": => Add_Header_Footer_Submit("FOT", $xtext, $html);
     * case "Add_Header_Submit": => Add_Header_Footer_Submit("HED", $xtext, $html);
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @param [type] $xtext
     * @param [type] $xhtml
     * @return void
     */
    public function Add_Header_Footer_Submit($ibid, $xtext, $xhtml)
    {
        if ($ibid == "HED")
            sql_query("INSERT INTO lnl_head_foot VALUES ('0', 'HED','$xhtml', '$xtext', 'OK')");
        else
            sql_query("INSERT INTO lnl_head_foot VALUES ('0', 'FOT', '$xhtml', '$xtext', 'OK')");

        header("location: admin.php?op=lnl");
    }

}
