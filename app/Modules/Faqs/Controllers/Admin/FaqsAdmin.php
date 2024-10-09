<?php

namespace App\Modules\Faqs\Controllers\Admin;

use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Facades\Css;
use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class FaqsAdmin extends AdminController
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
    protected $hlpfile = "faqs";

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
    protected $f_meta_nom = 'FaqAdmin';


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
        $this->f_titre = __d('faqs', 'Faq');

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
     * [faqadmin description]
     *
     * @return  [type]  [return description]
     */
    public function faqadmin()
    {
        $arg1 = '
            var formulid = ["adminfaqcatad"];
            inpandfieldlen("categories",255);
        ';

        $adminfoot = Css::adminfoot('fv', '', $arg1, '');

        $this->title($this->f_titre);

        $this->set('faqs', DB::table('faqcategories')->select('id_cat', 'categories')->orderBy('id_cat', 'asc')->get());

        $this->set('adminfoot', $adminfoot);

    }

}
