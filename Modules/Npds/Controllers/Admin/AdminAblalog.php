<?php

namespace Modules\Npds\Controllers\Admin;


use Modules\Npds\Core\AdminController;


class AdminAblalog extends AdminController
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
        // include("auth.php");

        // $f_meta_nom = 'abla';
        // $f_titre = __d('npds', 'Tableau de bord');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = 'language/manuels/' . Config::get('npds.language') . '/abla.html';
    // }

    public function index()
    {
        // global $admin;
        // if ($admin) {
        
        //     list($membres, $totala, $totalb, $totalc, $totald, $totalz) = req_stat();
        
        //     //LNL Email in outside table
        //     $result = sql_query("SELECT email FROM lnl_outside_users");
        //     if ($result) {
        //         $totalnl = sql_num_rows($result);
        //     } else {
        //         $totalnl = "0";
        //     }
        
        //     include("storage/ablalog/log.php");
        
        //     $timex = time() - $xdate;
        //     if ($timex >= 86400)
        //         $timex = round($timex / 86400) . ' ' . __d('npds', 'Jour(s)');
        //     elseif ($timex >= 3600)
        //         $timex = round($timex / 3600) . ' ' . __d('npds', 'Heure(s)');
        //     elseif ($timex >= 60)
        //         $timex = round($timex / 60) . ' ' . __d('npds', 'Minute(s)');
        //     else
        //         $timex = $timex . ' ' . __d('npds', 'Seconde(s)');
        
        //     echo '
        //    <hr />
        //    <p class="lead mb-3">' . __d('npds', 'Statistiques générales') . ' - ' . __d('npds', 'Dernières stats') . ' : ' . $timex . ' </p>
        //    <table class="mb-2" data-toggle="table" data-classes="table mb-2">
        //       <thead class="collapse thead-default">
        //          <tr>
        //             <th class="n-t-col-xs-9"></th>
        //             <th class="text-end"></th>
        //          </tr>
        //       </thead>
        //       <tbody>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. pages vues') . ' : </td>
        //             <td>' . wrh($totalz) . ' (';
        
        //     if ($totalz > $xtotalz)
        //         echo '<span class="text-success">+';
        //     elseif ($totalz < $xtotalz)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totalz - $xtotalz) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. de membres') . ' : </td>
        //             <td>' . wrh($membres) . ' (';
        
        //     if ($membres > $xmembres)
        //         echo '<span class="text-success">+';
        //     elseif ($membres < $xmembres)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($membres - $xmembres) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. d'articles') . ' : </td>
        //             <td>' . wrh($totala) . ' (';
        
        //     if ($totala > $xtotala)
        //         echo '<span class="text-success">+';
        //     elseif ($totala < $xtotala)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totala - $xtotala) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. de forums') . ' : </td>
        //             <td>' . wrh($totalc) . ' (';
        
        //     if ($totalc > $xtotalc)
        //         echo '<span class="text-success">+';
        //     elseif ($totalc < $xtotalc)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totalc - $xtotalc) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. de sujets') . ' : </td>
        //             <td>' . wrh($totald) . ' (';
        
        //     if ($totald > $xtotald)
        //         echo '<span class="text-success">+';
        //     elseif ($totald < $xtotald)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totald - $xtotald) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb. de critiques') . ' : </td>
        //             <td>' . wrh($totalb) . ' (';
        
        //     if ($totalb > $xtotalb)
        //         echo '<span class="text-success">+';
        //     elseif ($totalb < $xtotalb)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totalb - $xtotalb) . '</span>)</td>
        //          </tr>
        //          <tr>
        //             <td>' . __d('npds', 'Nb abonnés à lettre infos') . ' : </td>
        //             <td>' . wrh($totalnl) . ' (';
        
        //     if ($totalnl > $xtotalnl)
        //         echo '<span class="text-success">+';
        //     elseif ($totalnl < $xtotalnl)
        //         echo '<span class="text-danger">';
        //     else
        //         echo '<span>';
        
        //     echo wrh($totalnl - $xtotalnl) . '</span>)</td>
        //          </tr>';
        
        //     $xfile = "<?php\n";
        //     $xfile .= "\$xdate = " . time() . ";\n";
        //     $xfile .= "\$xtotalz = $totalz;\n";
        //     $xfile .= "\$xmembres = $membres;\n";
        //     $xfile .= "\$xtotala = $totala;\n";
        //     $xfile .= "\$xtotalc = $totalc;\n";
        //     $xfile .= "\$xtotald = $totald;\n";
        //     $xfile .= "\$xtotalb = $totalb;\n";
        //     $xfile .= "\$xtotalnl = $totalnl;\n";
        
        //     echo '
        //       </tbody>
        //    </table>
        //    <p class="lead my-3">' . __d('npds', 'Statistiques des chargements') . '</p>
        //    <table data-toggle="table" data-classes="table">
        //       <thead class=" thead-default">
        //          <tr>
        //             <th class="n-t-col-xs-9"></th>
        //             <th class="text-end"></th>
        //          </tr>
        //       </thead>
        //       <tbody>';
        
        //     $num_dow = 0;
        //     $result = sql_query("SELECT dcounter, dfilename FROM downloads");
        
        //     settype($xdownload, 'array');
        
        //     while (list($dcounter, $dfilename) = sql_fetch_row($result)) {
        //         $num_dow++;
        
        //         echo '
        //          <tr>
        //             <td><span class="text-danger">';
        
        //         if (array_key_exists($num_dow, $xdownload))
        //             echo $xdownload[$num_dow][1];
        
        //         echo '</span> -/- ' . $dfilename . '</td>
        //             <td><span class="text-danger">';
        
        //         if (array_key_exists($num_dow, $xdownload))
        //             echo $xdownload[$num_dow][2];
        
        //         echo '</span> -/- ' . $dcounter . '</td>
        //          </tr>';
        
        //         $xfile .= "\$xdownload[$num_dow][1] = \"$dfilename\";\n";
        //         $xfile .= "\$xdownload[$num_dow][2] = \"$dcounter\";\n";
        //     }
        
        //     echo '
        //       </tbody>
        //    </table>
        //    <p class="lead my-3">Forums</p>
        //    <table class="table table-bordered table-sm" data-classes="table">
        //       <thead class="">
        //          <tr>
        //             <th>' . __d('npds', 'Forum') . '</th>
        //             <th class="n-t-col-xs-2 text-center">' . __d('npds', 'Sujets') . '</th>
        //             <th class="n-t-col-xs-2 text-center">' . __d('npds', 'Contributions') . '</th>
        //             <th class="n-t-col-xs-3 text-end">' . __d('npds', 'Dernières contributions') . '</th>
        //          </tr>
        //       </thead>';
        
        //     $result = sql_query("SELECT * FROM catagories ORDER BY cat_id");
        //     $num_for = 0;
        
        //     while (list($cat_id, $cat_title) = sql_fetch_row($result)) {
        //         $sub_sql = "SELECT f.*, u.uname FROM forums f, users u WHERE f.cat_id = '$cat_id' AND f.forum_moderator = u.uid ORDER BY forum_index,forum_id";
                
        //         if (!$sub_result = sql_query($sub_sql)) 
        //             forumerror('0022');
        
        //         if ($myrow = sql_fetch_assoc($sub_result)) {
        //             echo '
        //             <tbody>
        //             <tr>
        //                <td class="table-active" colspan="4">' . stripslashes($cat_title) . '</td>
        //             </tr>';
        
        //             do {
        //                 $num_for++;
        //                 $last_post = get_last_post($myrow['forum_id'], 'forum', 'infos', true);
        //                 echo '<tr>';
        
        //                 $total_topics = get_total_topics($myrow['forum_id']);
        
        //                 $name = stripslashes($myrow['forum_name']);
        
        //                 $xfile .= "\$xforum[$num_for][1] = \"$name\";\n";
        //                 $xfile .= "\$xforum[$num_for][2] = $total_topics;\n";
        
        //                 $desc = stripslashes($myrow['forum_desc']);
        
        //                 echo '<td>
        //                     <a tabindex="0" role="button" data-bs-trigger="focus" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="' . $desc . '">
        //                         <i class="far fa-lg fa-file-alt me-2"></i></a><a href="viewforum.php?forum=' . $myrow['forum_id'] . '" >
        //                             <span class="text-danger">';
        
        //                 if (array_key_exists($num_for, $xforum))
        //                     echo $xforum[$num_for][1];
        
        //                 echo '</span> -/- ' . $name . ' </a></td>
        //                     <td class="text-center"><span class="text-danger">';
        
        //                 if (array_key_exists($num_for, $xforum))
        //                     echo $xforum[$num_for][2];
        
        //                 echo '</span> -/- ' . $total_topics . '</td>';
                        
        //                 $total_posts = get_total_posts($myrow['forum_id'], "", "forum", false);
        //                 $xfile .= "\$xforum[$num_for][3] = $total_posts;\n";
        
        //                 echo '<td class="text-center"><span class="text-danger">';
        
        //                 if (array_key_exists($num_for, $xforum))
        //                     echo $xforum[$num_for][3];
        
        //                 echo '</span> -/- ' . $total_posts . '</td>
        //                 <td class="text-end small">' . $last_post . '</td>';
        
        //             } while ($myrow = sql_fetch_assoc($sub_result));
        //         }
        //     }
            
        //     echo '
        //          </tr>
        //       </tbody>
        //    </table>';
        
        //     $file = fopen("storage/ablalog/log.php", "w");
        //     $xfile .= "? >\n";
        //     fwrite($file, $xfile);
        //     fclose($file);
        
        //     adminfoot('', '', '', '');
        // } else
        //     redirect_url("index.php");
    }

}
