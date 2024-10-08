<?php

namespace App\Modules\Blocks\Controllers\Admin;

use App\Modules\Npds\Support\Sanitize;
use App\Modules\Npds\Core\AdminController;
use App\Modules\Npds\Support\Facades\Css;

/**
 * Undocumented class
 */
class BlockAdmin extends AdminController
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
        // $f_meta_nom = 'ablock';
        // $f_titre = __d('blocks', 'Bloc Administration');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/adminblock.html";

        // switch ($op) {
        //     case 'ablock':
        //         ablock();
        //         break;
        
        //     case 'changeablock':
        //         changeablock($title, $content);
        //         break;
        // }
    // }

    /**
     * [ablock description]
     *
     * @return  [type]  [return description]
     */
    public function ablock()
    {
        $result = sql_query("SELECT title, content FROM block WHERE id=2");
    
        if (sql_num_rows($result) > 0) {

            list($title, $content) = sql_fetch_row($result);

            $arg1 = '
                var formulid = ["adminblock"];
                inpandfieldlen("title",1000);';

        }
    
        Css::adminfoot('fv', '', $arg1, '');
    }
    
    /**
     * [changeablock description]
     *
     * @param   [type]  $title    [$title description]
     * @param   [type]  $content  [$content description]
     *
     * @return  [type]            [return description]
     */
    public function changeablock($title, $content)
    {
        $title      = stripslashes(Sanitize::FixQuotes($title));
        $content    = stripslashes(Sanitize::FixQuotes($content));
    
        sql_query("UPDATE block SET title='$title', content='$content' WHERE id='2'");
    
        Ecr_Log('security', "ChangeAdminBlock() by AID : $this->aid", '');
    
        Header("Location: admin.php?op=adminMain");
    }

}
