<?php

namespace App\Modules\Download\Controllers\Front;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class DownloadTransfertFile extends FrontController
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
     * @return void
     * 
     * case : mydown
     */
    public function transferfile($did)
    {
        $result = sql_query("SELECT dcounter, durl, perms FROM downloads WHERE did='$did'");
        list($dcounter, $durl, $dperm) = sql_fetch_row($result);
    
        if (!$durl) {

            echo '
            <h2>' . __d('download', 'Chargement de fichiers') . '</h2>
            <hr />
            <div class="lead alert alert-danger">' . __d('download', 'Ce fichier n\'existe pas ...') . '</div>';

        } else {
            if (stristr($dperm, ',')) {
                $ibid = explode(',', $dperm);
    
                foreach ($ibid as $v) {
                    $aut = true;
    
                    if (autorisation($v) == true) {
                        $dcounter++;
                        sql_query("UPDATE downloads SET dcounter='$dcounter' WHERE did='$did'");
    
                        header("location: " . str_replace(basename($durl), rawurlencode(basename($durl)), $durl));
                        break;
                    } else
                        $aut = false;
                }
    
                if ($aut == false)
                    Header("Location: download.php");
            } else {
                if (autorisation($dperm)) {
                    $dcounter++;
    
                    sql_query("UPDATE downloads SET dcounter='$dcounter' WHERE did='$did'");
                    header("location: " . str_replace(basename($durl), rawurlencode(basename($durl)), $durl));
                } else
                    Header("Location: download.php");
            }
        }
    }

}
