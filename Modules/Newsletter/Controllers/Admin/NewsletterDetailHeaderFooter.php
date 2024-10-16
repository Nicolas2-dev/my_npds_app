<?php

namespace Modules\Newsletter\Controllers\Admin;

use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;
use Shared\Editeur\Support\Facades\Editeur;


/**
 * Undocumented class
 */
class NewsletterDetailHeaderFooter extends AdminController
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
     * case "Shw_Header":  => Detail_Header_Footer($Headerid, "HED");
     * case "Shw_Footer":  => Detail_Header_Footer($Footerid, "FOT");
     * 
     * Undocumented function
     *
     * @param [type] $ibid
     * @param [type] $type
     * @return void
     */
    public function Detail_Header_Footer($ibid, $type)
    {
        // $type = HED or FOT
        $result = sql_query("SELECT text, html FROM lnl_head_foot WHERE type='$type' AND ref='$ibid'");
        $tmp = sql_fetch_row($result);
    
        echo '
        <hr />
        <h3 class="mb-2">';
    
        if ($type == "HED")
            echo __d('newsletter', 'Message d\'entête');
        else
            echo __d('newsletter', 'Message de pied de page');
    
        echo ' - ' . __d('newsletter', 'Prévisualiser');
    
        if ($tmp[1] == 1)
            echo '<code> HTML</code></h3>
            <div class="card card-body">' . $tmp[0] . '</div>';
        else
            echo '<code>' . __d('newsletter', 'TEXTE') . '</code></h3>
            <div class="card card-body">' . nl2br($tmp[0]) . '</div>';
    
        echo '
        <hr />
        <form action="admin.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-12" for="xtext">' . __d('newsletter', 'Texte') . '</label>
                <div class="col-sm-12">
                    <textarea class="tin form-control" cols="70" rows="20" name="xtext" >' . htmlspecialchars($tmp[0], ENT_COMPAT | ENT_HTML401, cur_charset) . '</textarea>
                </div>
            </div>';
    
        if ($tmp[1] == 1) {
            global $tiny_mce_relurl;
            $tiny_mce_relurl = 'false';
            echo Editeur::aff_editeur('xtext', '');
        }
    
        if ($type == 'HED')
            echo '
            <input type="hidden" name="op" value="lnl_Add_Header_Mod" />';
        else
            echo '
            <input type="hidden" name="op" value="lnl_Add_Footer_Mod" />';
    
        echo '
            <input type="hidden" name="ref" value="' . $ibid . '" />
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button class="btn btn-primary me-1" type="submit">' . __d('newsletter', 'Valider') . '</button>
                    <a class="btn btn-secondary" href="admin.php?op=lnl" >' . __d('newsletter', 'Retour en arrière') . '</a>
                </div>
            </div>
        </form>';
    
        Css::adminfoot('', '', '', '');
    }

}
