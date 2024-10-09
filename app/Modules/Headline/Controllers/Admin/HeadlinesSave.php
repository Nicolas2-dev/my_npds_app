<?php

namespace App\Modules\Headline\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class HeadlinesSave extends AdminController
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
    protected $hlpfile = 'headlines';

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
    protected $f_meta_nom = 'HeadlinesAdmin';


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
        $this->f_titre = __d('headline', 'Grands Titres de sites de News');

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
     * @param [type] $hid
     * @param [type] $xsitename
     * @param [type] $url
     * @param [type] $headlinesurl
     * @param [type] $status
     * @return void
     */
    public function HeadlinesSave($hid, $xsitename, $url, $headlinesurl, $status)
    {
        sql_query("UPDATE headlines SET sitename='$xsitename', url='$url', headlinesurl='$headlinesurl', status='$status' WHERE hid='$hid'");
        
        Header("Location: admin.php?op=HeadlinesAdmin");
    }

}
