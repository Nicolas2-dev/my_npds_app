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
     * Undocumented function
     *
     * @param [type] $Yheader
     * @param [type] $Ybody
     * @param [type] $Yfooter
     * @return void
     */
    public function Test($Yheader, $Ybody, $Yfooter)
    {
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='HED' AND ref='$Yheader'");
        $Xheader = sql_fetch_row($result);
    
        $result = sql_query("SELECT text, html FROM lnl_body WHERE html='$Xheader[1]' AND ref='$Ybody'");
        $Xbody = sql_fetch_row($result);
    
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='FOT' AND html='$Xheader[1]' AND ref='$Yfooter'");
        $Xfooter = sql_fetch_row($result);
    
        // For Meta-Lang
        //   global $cookie; // a quoi ca sert
        //   $uid=$cookie[0]; // a quoi ca sert
    
        if ($Xheader[1] == 1) {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('newsletter', 'Prévisualiser') . ' HTML</h3>';
    
            $Xmime = 'html-nobr';
            $message = meta_lang($Xheader[0] . $Xbody[0] . $Xfooter[0]);
        } else {
            echo '
            <hr />
            <h3 class="mb-3">' . __d('newsletter', 'Prévisualiser') . ' ' . __d('newsletter', 'TEXTE') . '</h3>';
    
            $Xmime = 'text';
            $message = meta_lang(nl2br($Xheader[0]) . nl2br($Xbody[0]) . nl2br($Xfooter[0]));
        }
    
        echo '
        <div class="card card-body">
        ' . $message . '
        </div>
        <a class="btn btn-secondary my-3" href="javascript:history.go(-1)" >' . __d('newsletter', 'Retour en arrière') . '</a>';
    
        send_email(Config::get('npds.adminmail'), 'LNL TEST', $message, Config::get('npds.adminmail'), true, $Xmime, '');
    
        adminfoot('', '', '', '');
    }

}
