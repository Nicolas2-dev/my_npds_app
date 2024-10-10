<?php

namespace Modules\Headline\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class HeadlinesDel extends AdminController
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
     * @param integer $ok
     * @return void
     */
    public function HeadlinesDel($hid, $ok = 0)
    {
        if ($ok == 1) {
            sql_query("DELETE FROM headlines WHERE hid='$hid'");
    
            Header("Location: admin.php?op=HeadlinesAdmin");
        } else {
            echo '
            <hr />
            <p class="alert alert-danger">
                <strong class="d-block mb-1">' . __d('headline', 'Etes-vous sûr de vouloir supprimer cette boîte de Titres ?') . '</strong>
                <a class="btn btn-danger btn-sm" href="admin.php?op=HeadlinesDel&amp;hid=' . $hid . '&amp;ok=1" role="button">' . __d('headline', 'Oui') . '</a>&nbsp;<a class="btn btn-secondary btn-sm" href="admin.php?op=HeadlinesAdmin" role="button">' . __d('headline', 'Non') . '</a>
            </p>';
        }
    }

}
