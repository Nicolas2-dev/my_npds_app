<?php

namespace Modules\download\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class DownloadSave extends AdminController
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
    protected $hlpfile = 'downloads';

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
    protected $f_meta_nom = 'DownloadAdmin';


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
        $this->f_titre = __d('download', 'Téléchargements');

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
     * @param [type] $did
     * @param [type] $dcounter
     * @param [type] $durl
     * @param [type] $dfilename
     * @param [type] $dfilesize
     * @param [type] $dweb
     * @param [type] $duser
     * @param [type] $ddate
     * @param [type] $dver
     * @param [type] $dcategory
     * @param [type] $sdcategory
     * @param [type] $description
     * @param [type] $privs
     * @param [type] $Mprivs
     * @return void
     */
    public function DownloadSave($did, $dcounter, $durl, $dfilename, $dfilesize, $dweb, $duser, $ddate, $dver, $dcategory, $sdcategory, $description, $privs, $Mprivs)
    {
        if ($privs == 1) {
            if ($Mprivs != '')
                $privs = implode(',', $Mprivs);
        }

        $sdcategory = addslashes($sdcategory);
        $dcategory = (!$dcategory) ? $sdcategory : addslashes($dcategory);
        $description = addslashes($description);

        if ($ddate == "yes") {
            $time = date("Y-m-d");

            sql_query("UPDATE downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', ddate='$time', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
        } else
            sql_query("UPDATE downloads SET dcounter='$dcounter', durl='$durl', dfilename='$dfilename', dfilesize='$dfilesize', dweb='$dweb', duser='$duser', dver='$dver', dcategory='$dcategory', ddescription='$description', perms='$privs' WHERE did='$did'");
        
        Header("Location: admin.php?op=DownloadAdmin");
    }

}
