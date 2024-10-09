<?php

namespace App\Modules\Download\Controllers\Front;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class DownloadInfo extends FrontController
{
 
    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
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
     * @param [type] $did
     * @param [type] $out_template
     * @return void
     */
    public function geninfo($did, $out_template)
    {
        $result = sql_query("SELECT dcounter, durl, dfilename, dfilesize, ddate, dweb, duser, dver, dcategory, ddescription, perms FROM downloads WHERE did='$did'");
        list($dcounter, $durl, $dfilename, $dfilesize, $ddate, $dweb, $duser, $dver, $dcategory, $ddescription, $dperm) = sql_fetch_row($result);
    
        $okfile = false;
        if (!stristr($dperm, ','))
            $okfile = autorisation($dperm);
        else {
            $ibidperm = explode(',', $dperm);
    
            foreach ($ibidperm as $v) {
                if (autorisation($v)) {
                    $okfile = true;
                    break;
                }
            }
        }
    
        if ($okfile) {
            $title = $dfilename;
    
            if ($out_template == 1) {
                include('header.php');
    
                echo '
                <h2 class="mb-3">' . __d('download', 'Chargement de fichiers') . '</h2>
                <div class="card">
                    <div class="card-header"><h4>' . $dfilename . '<span class="ms-3 text-muted small">@' . $durl . '</h4></div>
                    <div class="card-body">';
            }
    
            echo '<p><strong>' . __d('download', 'Taille du fichier') . ' : </strong>';
    
            $Fichier = new File($durl);
            $objZF = new FileManagement;
    
            if ($dfilesize != 0)
                echo $objZF->file_size_format($dfilesize, 1);
            else
                echo $objZF->file_size_auto($durl, 2);
    
            echo '</p>
                    <p><strong>' . __d('download', 'Version') . '&nbsp;:</strong>&nbsp;' . $dver . '</p>
                    <p><strong>' . __d('download', 'Date de chargement sur le serveur') . '&nbsp;:</strong>&nbsp;' . convertdate($ddate) . '</p>
                    <p><strong>' . __d('download', 'Chargements') . '&nbsp;:</strong>&nbsp;' . wrh($dcounter) . '</p>
                    <p><strong>' . __d('download', 'Catégorie') . '&nbsp;:</strong>&nbsp;' . aff_langue(stripslashes($dcategory)) . '</p>
                    <p><strong>' . __d('download', 'Description') . '&nbsp;:</strong>&nbsp;' . aff_langue(stripslashes($ddescription)) . '</p>
                    <p><strong>' . __d('download', 'Auteur') . '&nbsp;:</strong>&nbsp;' . $duser . '</p>
                    <p><strong>' . __d('download', 'Page d\'accueil') . '&nbsp;:</strong>&nbsp;<a href="http://' . $dweb . '" target="_blank">' . $dweb . '</a></p>';
    
            if ($out_template == 1) {
                echo '
                    <a class="btn btn-primary" href="download.php?op=mydown&amp;did=' . $did . '" target="_blank" title="' . __d('download', 'Charger maintenant') . '" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-lg fa-download"></i></a>
                    </div>
                </div>';
            }
        } else
            Header("Location: download.php");
    }

}
