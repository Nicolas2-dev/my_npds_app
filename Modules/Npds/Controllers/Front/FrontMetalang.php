<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontMetalang extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public function index()
    {
        global $user;

        if (isset($user) and $user != '') {
            global $cookie;
        
            if ($cookie[9] != '') {
                $ibix = explode('+', urldecode($cookie[9]));
        
                if (array_key_exists(0, $ibix)) 
                    $theme = $ibix[0];
                else 
                    $theme = Config::get('npds.Default_Theme');
        
                if (array_key_exists(1, $ibix)) 
                    $skin = $ibix[1];
                else 
                    $skin = Config::get('npds.Default_Skin'); //$skin=''; 
        
                $tmp_theme = $theme;
        
                if (!$file = @opendir("themes/$theme")) 
                    $tmp_theme = Config::get('npds.Default_Theme');
            } else
                $tmp_theme = Config::get('npds.Default_Theme');
        } else {
            $theme = Config::get('npds.Default_Theme');
            $skin = Config::get('npds.Default_Skin');
            $tmp_theme = $theme;
        }
        
        include("themes/$tmp_theme/theme.php");
        
        $Titlesitename = "META-LANG";
        
        include("storage/meta/meta.php");
        
        echo import_css($tmp_theme, $skin, '', '');
        
        
        $Q = sql_query("SELECT def, content, type_meta, type_uri, uri, description FROM metalang ORDER BY 'type_meta','def' ASC");
        
        echo '
            <div class="p-2">
            <table class="table table-striped table-responsive table-hover table-sm table-bordered" >
                <thead class="thead-default">
                    <tr>
                        <th>META</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>';
        
        $cur_type = '';
        $ibid = 0;
        
        while (list($def, $content, $type_meta, $type_uri, $uri, $description) = sql_fetch_row($Q)) {
            if ($cur_type == '')
                $cur_type = $type_meta;
        
            if ($type_meta != $cur_type) {
                echo '
                    </tr>
                    <tr>
                        <td class="lead" colspan="3">' . $type_meta . '</td>
                    </tr>
                <tbody>';
        
                $cur_type = $type_meta;
            }
        
            if (isset($_SERVER['HTTP_REFERER']) and strstr($_SERVER['HTTP_REFERER'], 'submit.php'))
                $def_modifier = "<a class=\"tooltipbyclass\" href=\"#\" onclick=\"javascript:parent.tinymce.activeEditor.selection.setContent(' " . $def . " ');top.tinymce.activeEditor.windowManager.close();
        \" title=\"Cliquer pour utiliser ce méta-mot dans votre texte.\">$def</a>";
            else
                $def_modifier = $def;
        
            echo '
                    <tr>
                        <td valign="top" align="left"><strong>' . $def_modifier . '</strong></td>
                        <td class="table-secondary" valign="top" align="left">' . $type_meta . '</td>';
        
            if ($type_meta == "smil") {
                eval($content);
                echo '
                        <td valign="top" align="left">' . $cmd . '</td>
                    </tr>';
            } else if ($type_meta == "mot")
                echo '
                        <td valign="top" align="left">' . $content . '</td>
                    </tr>';
            else
                echo '
                        <td valign="top" align="left">' . aff_langue($description) . '</td>
                    </tr>';
        
            $ibid++;
        }
        
        echo '
                    <tr><td colspan="3" >Meta-lang pour <a href="http://www.App.org" >App</a> ==> ' . $ibid . ' META(s)
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>';
        
        echo '
            <script type="text/javascript" src="assets/shared/jquery/jquery.min.js"></script>
            <script type="text/javascript" src="assets/shared/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script type="text/javascript" src="assets/js/npds_adapt.js"></script>';
        
        
    }

}