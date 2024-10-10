<?php

namespace Modules\Npds\Controllers\Api;

use Npds\Support\Facades\DB;
use App\Controllers\Core\BaseController;


class ApiAlerte extends BaseController
{

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $autoRender = false;


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
     * @return void
     */
    public function alerte_api()
    {
        if (isset($_POST['id'])) {
            
            $alert = DB::table('fonctions')->select('*')->where('fid', $_POST['id'])->first();
    
            if (isset($alert)) {
                $data = [];
                
                if (count($alert) > 0) {
                    $data = $alert;
                }

                echo json_encode($data);    
            }  
        }
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function alerte_update()
    {
        global $admin;
    
        $Xadmin = base64_decode($admin);
        $Xadmin = explode(':', $Xadmin);
        $aid    = urlencode($Xadmin[0]);
    
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            
            $result = sql_query("SELECT * FROM fonctions WHERE fid=" . $id . "");
            $row = sql_fetch_assoc($result);
    
            $newlecture = $aid . '|' . $row['fdroits1_descr'];

            sql_query("UPDATE fonctions SET fdroits1_descr='" . $newlecture . "' WHERE fid=" . $id . "");
        }
    
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
