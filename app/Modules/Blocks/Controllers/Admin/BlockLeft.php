<?php

namespace App\Modules\Blocks\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;

/**
 * Undocumented class
 */
class BlockLeft extends AdminController
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
        

        // $hlpfile = "language/manuels/Config::get('npds.language')/leftblocks.html";
  
        // settype($css, 'integer');

        // $Mmember = isset($Mmember) ? $Mmember : '';
        
        // settype($Sactif, 'string');
        // settype($SHTML, 'string');
        
        // switch ($op) {
        //     case 'makelblock':
        //         makelblock($title, $xtext, $members, $Mmember, $index, $Scache, $Baide, $SHTML, $css);
        //         break;
        
        //     case 'deletelblock':
        //         deletelblock($id);
        //         break;
        
        //     case 'changelblock':
        //         changelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css);
        //         break;
                
        //     case 'droitelblock':
        //         changedroitelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css);
        //         break;
        // }
    // }

    function makelblock($title, $content, $members, $Mmember, $Lindex, $Scache, $BLaide, $SHTML, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Lindex)) 
            $Lindex = 0;
    
        $title = stripslashes(FixQuotes($title));
        $content = stripslashes(FixQuotes($content));
    
        if ($SHTML != 'ON')
            $content = strip_tags(str_replace('<br />', '\n', $content));
    
        sql_query("INSERT INTO lblocks VALUES (NULL,'$title','$content','$members', '$Lindex', '$Scache', '1','$css', '$BLaide')");
    
        global $aid;
        Ecr_Log('security', "MakeLeftBlock(" . aff_langue($title) . ") by AID : $aid", "");
    
        Header("Location: admin.php?op=blocks");
    }
    
    function changelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Lindex)) 
            $Lindex = 0;
    
        $title = stripslashes(FixQuotes($title));
    
        if ($Sactif == 'ON') 
            $Sactif = 1;
        else 
            $Sactif = 0;
    
        if ($css) 
            $css = 1;
        else 
            $css = 0;
    
        $content = stripslashes(FixQuotes($content));
        $BLaide = stripslashes(FixQuotes($BLaide));
    
        sql_query("UPDATE lblocks SET title='$title', content='$content', member='$members', Lindex='$Lindex', cache='$Scache', actif='$Sactif', aide='$BLaide', css='$css' WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "ChangeLeftBlock(" . aff_langue($title) . " - $id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }
    
    function changedroitelblock($id, $title, $content, $members, $Mmember, $Lindex, $Scache, $Sactif, $BLaide, $css)
    {
        
    
        if (is_array($Mmember) and ($members == 1)) {
            $members = implode(',', $Mmember);
    
            if ($members == 0) 
                $members = 1;
        }
    
        if (empty($Lindex)) {
            $Lindex = 0;
        }
    
        $title = stripslashes(FixQuotes($title));
    
        if ($Sactif == 'ON') 
            $Sactif = 1;
        else    
            $Sactif = 0;
    
        if ($css) 
            $css = 1;
        else 
            $css = 0;
    
        $content = stripslashes(FixQuotes($content));
        $BLaide = stripslashes(FixQuotes($BLaide));
    
        sql_query("INSERT INTO rblocks VALUES (NULL,'$title','$content', '$members', '$Lindex', '$Scache', '$Sactif', '$css', '$BLaide')");
        sql_query("DELETE FROM lblocks WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "MoveLeftBlockToRight(" . aff_langue($title) . " - $id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }
    
    function deletelblock($id)
    {
        
    
        sql_query("DELETE FROM lblocks WHERE id='$id'");
    
        global $aid;
        Ecr_Log('security', "DeleteLeftBlock($id) by AID : $aid", '');
    
        Header("Location: admin.php?op=blocks");
    }


}