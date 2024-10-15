<?php

namespace Modules\Npds\Controllers\Api;

use Npds\Config\Config;
use App\Controllers\Core\BaseController;


class ApiDatabase extends BaseController
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
    public static function mysqli_deprecated()
    {
        global $dblink;

        // a supprimer par la suite
        if (Config::get('npds.mysql_i') == 1) {
            include(shared_path('DatabaseNpds/mysqli.php'));
        } else {
            include(shared_path('DatabaseNpds/mysql.php'));
        }

        // a supprimer par la suite
        $dblink = Mysql_Connexion();
    }

}
