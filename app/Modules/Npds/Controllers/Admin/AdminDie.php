<?php

namespace App\Modules\Npds\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminDie extends AdminController
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

    public function index()
    {
        $Titlesitename = 'Npds';

        if (file_exists("storage/meta/meta.php"))
            include("storage/meta/meta.php");
        
        echo '
            <link id="bsth" rel="stylesheet" href="/assets/shared/bootstrap/dist/css/bootstrap.min.css" />
            </head>
            <body>
                <div class="contenair-fluid mt-5">
                    <div class= "card mx-auto p-3" style="width:380px; text-align:center">
                        <span style="font-size: 72px;">🚫</span>
                        <span class="text-danger h3 mb-3" style="">
                        Acc&egrave;s refus&eacute; ! <br />
                        Access denied ! <br />
                        Zugriff verweigert ! <br />
                        Acceso denegado ! <br />
                        &#x901A;&#x5165;&#x88AB;&#x5426;&#x8BA4; ! <br />
                        </span>
                        <hr />
                        <div>
                        <span class="text-muted">App - Portal System</span>
                        <img width="48px" class="adm_img ms-2" src="/"assets/images/admin/message_App.png" alt="icon_App">
                        </div>
                    </div>
                </div>
            </body>
            </html>';
            die();        
    }

    function Access_Error()
    {
        include("admin/die.php");
    }

}
