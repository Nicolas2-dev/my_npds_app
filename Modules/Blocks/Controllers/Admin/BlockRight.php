<?php

namespace Modules\blocks\Controllers\Admin;

use Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class blockright extends AdminController
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
        // $f_meta_nom = 'blocks';

        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/rightblocks.html";

        // settype($css, 'integer');

        // $Mmember = isset($Mmember) ? $Mmember : '';
        
        // settype($Sactif, 'string');
        // settype($SHTML, 'string');
        
        // switch ($op) {
        //     case 'makerblock':
        //         makerblock($title, $xtext, $members, $Mmember, $index, $Scache, $Baide, $SHTML, $css);
        //         break;
        
        //     case 'deleterblock':
        //         deleterblock($id);
        //         break;
        
        //     case 'changerblock':
        //         changerblock($id, $title, $content, $members, $Mmember, $Rindex, $Scache, $Sactif, $BRaide, $css);
        //         break;
                
        //     case 'gaucherblock':
        //         changegaucherblock($id, $title, $content, $members, $Mmember, $Rindex, $Scache, $Sactif, $BRaide, $css);
        //         break;
        // }
    // }

    function makerblock($title, $content, $members, $Mmember, $Rindex, $Scache, $BRaide, $SHTML, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Rindex)) 
            $Rindex = 0;
    
        $title = stripslashes(FixQuotes($title));
        $content = stripslashes(FixQuotes($content));
    
        if ($SHTML != 'ON')
            $content = strip_tags(str_replace('<br />', "\n", $content));
    
        sql_query("INSERT INTO rblocks VALUES (NULL,'$title','$content', '$members', '$Rindex', '$Scache', '1', '$css', '$BRaide')");
    
        global $aid;
        Ecr_Log('security', "MakeRightBlock(" . aff_langue($title) . ") by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }
    
    function changerblock($id, $title, $content, $members, $Mmember, $Rindex, $Scache, $Sactif, $BRaide, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Rindex)) 
            $Rindex = 0;
    
        $title = stripslashes(FixQuotes($title));
    
        if ($Sactif == 'ON') 
            $Sactif = 1;
        else 
            $Sactif = 0;
    
        $content = stripslashes(FixQuotes($content));
        sql_query("UPDATE rblocks SET title='$title', content='$content', member='$members', Rindex='$Rindex', cache='$Scache', actif='$Sactif', css='$css', aide='$BRaide' WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "ChangeRightBlock(" . aff_langue($title) . " - $id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }
    
    function changegaucherblock($id, $title, $content, $members, $Mmember, $Rindex, $Scache, $Sactif, $BRaide, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Rindex)) 
            $Rindex = 0;
    
        $title = stripslashes(FixQuotes($title));
    
        if ($Sactif == 'ON') 
            $Sactif = 1;
        else 
            $Sactif = 0;
    
        $content = stripslashes(FixQuotes($content));
    
        sql_query("INSERT INTO lblocks VALUES (NULL,'$title','$content','$members', '$Rindex', '$Scache', '$Sactif', '$css', '$BRaide')");
        sql_query("DELETE FROM rblocks WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "MoveRightBlockToLeft(" . aff_langue($title) . " - $id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }
    
    function deleterblock($id)
    {
        
    
        sql_query("DELETE FROM rblocks WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "DeleteRightBlock($id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }


}