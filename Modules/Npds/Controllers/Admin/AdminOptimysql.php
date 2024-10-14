<?php

namespace Modules\Npds\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class AdminOptimysql extends AdminController
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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
    //     $f_meta_nom = 'OptimySQL';
    //     $f_titre = __d('npds', 'Optimisation de la base de données') . ' : ' . Config::get('npds.dbname');
        
    //     //==> controle droit
    //     admindroits($aid, $f_meta_nom);
    //     //<== controle droit
        
    //     $hlpfile = 'manuels/' . Config::get('npds.language') . '/optimysql.html';
    // }

    public function index()
    {
        $date_opt = date(__d('npds', 'dateforop'));
        $heure_opt = date("h:i a");

        // Insertion de valeurs d'initialisation de la table (si nécessaire)
        $result = sql_query("SELECT optid FROM optimy");
        list($idopt) = sql_fetch_row($result);
        
        if (!$idopt or ($idopt == ''))
            $result = sql_query("INSERT INTO optimy (optid, optgain, optdate, opthour, optcount) VALUES ('1', '0', '', '', '0')");
        
        // Extraction de la date et de l'heure de la précédente optimisation
        $last_opti = '';
        
        $result = sql_query("SELECT optdate, opthour FROM optimy WHERE optid='1'");
        list($dateopt, $houropt) = sql_fetch_row($result);
        
        if (!$dateopt or ($dateopt == '') or !$houropt or ($houropt == '')) {
        } else {
            $last_opti = __d('npds', 'Dernière optimisation effectuée le') . " : " . $dateopt . " " . __d('npds', ' à ') . " " . $houropt . "<br />\n";
        }
        
        $tot_data = 0;
        $tot_idx = 0;
        $tot_all = 0;
        $li_tab_opti = '';
        
        // si optimysql n'affiche rien - essayer avec la ligne ci-dessous
        //$result = sql_query("SHOW TABLE STATUS FROM `Config::get('npds.dbname')`";);
        $result = sql_query("SHOW TABLE STATUS FROM " . Config::get('npds.dbname'));
        
        if (sql_num_rows($result)) {
            while ($row = sql_fetch_assoc($result)) {
        
                $tot_data = $row['Data_length'];
                $tot_idx  = $row['Index_length'];
                $total = ($tot_data + $tot_idx);
                $total = ($total / 1024);
                $total = round($total, 3);
                $gain = $row['Data_free'];
                $gain = ($gain / 1024);
        
                settype($total_gain, 'integer');
        
                $total_gain += $gain;
                $gain = round($gain, 3);
                $resultat = sql_query("OPTIMIZE TABLE " . $row['Name'] . " ");
        
                if ($gain == 0)
                    $li_tab_opti .= '
                    <tr class="table-success">
                        <td align="right">' . $row['Name'] . '</td>
                        <td align="right">' . $total . ' Ko</td>
                        <td align="center">' . __d('npds', 'optimisée') . '</td>
                        <td align="center"> -- </td>
                    </tr>';
                else
                    $li_tab_opti .= '
                    <tr class="table-danger">
                        <td align="right">' . $row['Name'] . '</td>
                        <td align="right">' . $total . ' Ko</td>
                        <td class="text-danger" align="center">' . __d('npds', 'non optimisée') . '</td>
                        <td align="right">' . $gain . ' Ko</td>
                    </tr>';
            }
        }
        
        $total_gain = round($total_gain, 3);
        
        // Historique des gains
        // Extraction du nombre d'optimisation effectuée
        $result = sql_query("SELECT optgain, optcount FROM optimy WHERE optid='1'");
        list($gainopt, $countopt) = sql_fetch_row($result);
        
        $newgain = ($gainopt + $total_gain);
        $newcount = ($countopt + 1);
        
        // Enregistrement du nouveau gain
        $result = sql_query("UPDATE optimy SET optgain='$newgain', optdate='$date_opt', opthour='$heure_opt', optcount='$newcount' WHERE optid='1'");
        
        // Lecture des gains précédents et addition
        $result = sql_query("SELECT optgain, optcount FROM optimy WHERE optid='1'");
        list($gainopt, $countopt) = sql_fetch_row($result);
        
        // Affichage
        adminhead($f_meta_nom, $f_titre);
        
        echo '<hr /><p class="lead">' . __d('npds', 'Optimisation effectuée') . ' : ' . __d('npds', 'Gain total réalisé') . ' ' . $total_gain . ' Ko</br>';
        
        echo $last_opti;
        
        echo '
            ' . __d('npds', 'A ce jour, vous avez effectué ') . ' ' . $countopt . ' optimisation(s) ' . __d('npds', ' et réalisé un gain global de ') . ' ' . $gainopt . ' Ko.</p>
            <table id="tad_opti" data-toggle="table" data-striped="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
            <thead>
                <tr>
                    <th data-sortable="true" data-halign="center" data-align="center">' . __d('npds', 'Table') . '</th>
                    <th data-halign="center" data-align="center">' . __d('npds', 'Taille actuelle') . '</th>
                    <th data-sortable="true" data-halign="center" data-align="center">' . __d('npds', 'Etat') . '</th>
                    <th data-halign="center" date-align="center">' . __d('npds', 'Gain réalisable') . '</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>' . __d('npds', 'Gain total réalisé') . ' : </td>
                    <td>' . $total_gain . ' Ko</td>
                </tr>
            </tfoot>
            <tbody>';
        
        echo $li_tab_opti;
        
        echo '
            </tbody>
            </table>';
        
        adminfoot('', '', '', '');
        
        global $aid;
        Ecr_Log('security', "OptiMySql() by AID : $aid", '');
        
    }

}
