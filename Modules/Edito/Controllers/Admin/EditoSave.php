<?php

namespace Modules\Edito\Controllers\Admin;


use Modules\Npds\Core\AdminController;


class EditoSave extends AdminController
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
    protected $hlpfile = 'edito';

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
    protected $f_meta_nom = 'edito';


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
        $this->f_titre = __d('edito', 'Edito');

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
     * @param [type] $edito_type
     * @param [type] $XeditoJ
     * @param [type] $XeditoN
     * @param [type] $aff_jours
     * @param [type] $aff_jour
     * @param [type] $aff_nuit
     * @return void
     */
    public function edito_mod_save($edito_type, $XeditoJ, $XeditoN, $aff_jours, $aff_jour, $aff_nuit)
    {
        //edito_mod_save($edito_type, $XeditoJ, $XeditoN, $aff_jours, $aff_jour, $aff_nuit);

        if ($aff_jours <= 0) {
            $aff_jours = '999';
        }
    
        if ($edito_type == 'G') {
            $fp = fopen("storage/static/edito.txt", "w");
    
            fputs($fp, "[jour]" . str_replace('&quot;', '"', stripslashes($XeditoJ)) . '[/jour][nuit]' . str_replace('&quot;', '"', stripslashes($XeditoN)) . '[/nuit]');
            fputs($fp, 'aff_jours=' . $aff_jours);
            fputs($fp, '&aff_jour=' . $aff_jour);
            fputs($fp, '&aff_nuit=' . $aff_nuit);
            fputs($fp, '&aff_date=' . time());
            fclose($fp);
        } elseif ($edito_type == 'M') {
            $fp = fopen('storage/static/edito_membres.txt', 'w');
    
            fputs($fp, '[jour]' . str_replace('&quot;', '"', stripslashes($XeditoJ)) . '[/jour][nuit]' . str_replace('&quot;', '"', stripslashes($XeditoN)) . '[/nuit]');
            fputs($fp, 'aff_jours=' . $aff_jours);
            fputs($fp, '&aff_jour=' . $aff_jour);
            fputs($fp, '&aff_nuit=' . $aff_nuit);
            fputs($fp, '&aff_date=' . time());
            fclose($fp);
        }
    
        global $aid;
        Ecr_Log('security', "editoSave () by AID : $aid", '');
    
        redirect_url('admin.php?op=Edito');
    }

}
