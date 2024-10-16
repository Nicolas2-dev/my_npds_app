<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


/**
 * Undocumented class
 */
class NewsletterDelQuestion extends AdminController
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
     * case "Sup_Header": => Del_Question("lnl_Sup_HeaderOK", "Headerid=$Headerid");
     * case "Sup_Body":   => Del_Question("lnl_Sup_BodyOK", "Bodyid=$Bodyid");
     * case "Sup_Footer": => Del_Question("lnl_Sup_FooterOK", "Footerid=$Footerid");
     * 
     * Undocumented function
     *
     * @param [type] $retour
     * @param [type] $param
     * @return void
     */
    public function Del_Question($retour, $param)
    {
        echo '
        <hr />
        <div class="alert alert-danger">' . __d('newsletter', 'Etes-vous sûr de vouloir effacer cet Article ?') . '</div>
        <a href="admin.php?op=' . $retour . '&amp;' . $param . '" class="btn btn-danger btn-sm">' . __d('newsletter', 'Oui') . '</a>
        <a href="javascript:history.go(-1)" class="btn btn-secondary btn-sm">' . __d('newsletter', 'Non') . '</a>';
    
        Css::adminfoot('', '', '', '');
    }
    
}
