<?php

namespace Modules\blocks\Controllers\Admin;


use Modules\npds\Core\AdminController;

/**
 * Undocumented class
 */
class BlockMember extends AdminController
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
        // $f_meta_nom = 'mblock';
        // $f_titre = __d('blocks', 'Bloc Principal');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/mainblock.html";

        // switch ($op) {
        //     case 'mblock':
        //         mblock();
        //         break;
        
        //     case 'changemblock':
        //         changemblock($title, $content);
        //         break;
        // }  
    // }

    function mblock()
    {
        global $hlpfile, $f_meta_nom, $f_titre;
    
        include("header.php");
    
        GraphicAdmin($hlpfile);
        adminhead($f_meta_nom, $f_titre);
    
        echo '
        <hr />
        <h3>' . __d('blocks', 'Edition du Bloc Principal') . '</h3>';
    
        $result = sql_query("SELECT title, content FROM block WHERE id=1");
    
        if (sql_num_rows($result) > 0) {
            while (list($title, $content) = sql_fetch_row($result)) {
                echo '
                <form id="fad_mblock" action="admin.php" method="post">
                    <div class="form-floating mb-3">
                    <textarea class="form-control" type="text" id="title" name="title" maxlength="1000" placeholder="' . __d('blocks', 'Titre :') . '" style="height:70px;">' . $title . '</textarea>
                    <label for="title">' . __d('blocks', 'Titre') . '</label>
                    <span class="help-block text-end"><span id="countcar_title"></span></span>
                    </div>
                    <div class="form-floating mb-3">
                    <textarea class="form-control" id="content" name="content" style="height:170px;">' . $content . '</textarea>
                    <label for="content">' . __d('blocks', 'Contenu') . '</label>
                    </div>
                    <input type="hidden" name="op" value="changemblock" />
                    <button class="btn btn-primary btn-block" type="submit">' . __d('blocks', 'Valider') . '</button>
                </form>
                <script type="text/javascript">
                //<![CDATA[
                    $(document).ready(function() {
                    inpandfieldlen("title",1000);
                    });
                //]]>
                </script>';
            }
        }
    
        adminfoot('fv', '', '', '');
    }
    
    function changemblock($title, $content)
    {
        
    
        $title = stripslashes(FixQuotes($title));
        $content = stripslashes(FixQuotes($content));
    
        sql_query("UPDATE block SET title='$title', content='$content' WHERE id='1'");
    
        global $aid;
        Ecr_Log('security', "ChangeMainBlock(" . aff_langue($title) . ") by AID : $aid", '');
    
        Header("Location: admin.php?op=adminMain");
    }


}