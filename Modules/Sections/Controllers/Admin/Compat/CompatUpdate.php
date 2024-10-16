<?php

namespace Modules\Sections\Controllers\Admin\Compat;

use Modules\Npds\Core\AdminController;


class CompatUpdate extends AdminController
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
     * @param [type] $article
     * @param [type] $admin_rub
     * @param [type] $idx
     * @return void
     */
    public function updatecompat($article, $admin_rub, $idx)
    {
        $result = sql_query("DELETE FROM compatsujet WHERE id1='$article'");
    
        for ($j = 1; $j < ($idx + 1); $j++) {
            if ($admin_rub[$j] != '') {
                $result = sql_query("INSERT INTO compatsujet VALUES ('$article','$admin_rub[$j]')");
            }
        }
    
        global $aid;
        Ecr_Log('security', "UpdateCompatSujets($article) by AID : $aid", '');
    
        Header("Location: admin.php?op=secartedit&artid=$article");
    }

}
