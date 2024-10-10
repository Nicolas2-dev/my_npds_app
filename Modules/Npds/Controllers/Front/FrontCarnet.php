<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class Front extends FrontController
{


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {

    }

    public  function index()
    {
        global $user;

        if (!$user)
            Header("Location: user.php");
        else {
            $userX = base64_decode($user);
            $userdata = explode(':', $userX);
        
            if ($userdata[9] != '') {
                if (!$file = @opendir("themes/$userdata[9]"))
                    $tmp_theme = Config::get('npds.Default_Theme');
                else
                    $tmp_theme = $userdata[9];
            } else
                $tmp_theme = Config::get('npds.Default_Theme');
        
            include("themes/$tmp_theme/theme.php");
        
            $Titlesitename = __d('npds', 'Carnet d\'adresses');
        
            include("storage/meta/meta.php");
        
            echo '<link id="bsth" rel="stylesheet" href="assets/skins/default/bootstrap.min.css" />';
        
            echo import_css($tmp_theme, "", "", "");
        
            include("library/javascript/formhelp.java.php");
        
            $fic = "storage/users_private/" . $userdata[1] . "/mns/carnet.txt";
        
            echo '
            </head>
            <body class="p-4">';
        
            if (file_exists($fic)) {
                $fp = fopen($fic, "r");
                if (filesize($fic) > 0)
                    $contents = fread($fp, filesize($fic));
                fclose($fp);
        
                if (substr($contents, 0, 5) != "CRYPT") {
                    $fp = fopen($fic, "w");
                    fwrite($fp, "CRYPT" . L_encrypt($contents));
                    fclose($fp);
                } else {
                    $contents = decryptK(substr($contents, 5), substr($userdata[2], 8, 8));
                }
        
                echo '<div class="row">';
        
                $contents = explode("\n", $contents);
        
                foreach ($contents as $tab) {
                    $tabi = explode(';', $tab);
        
                    if ($tabi[0] != '') {
                        echo '
                        <div class="border col-md-4 mb-1 p-3">
                            <a href="javascript: DoAdd(1,\'to_user\',\'' . $tabi[0] . ',\')";><b>' . $tabi[0] . '</b></a><br />
                            <a href="mailto:' . $tabi['1'] . '" >' . $tabi['1'] . '</a><br />
                            ' . $tabi['2'] . '
                        </div>';
                    }
                }
        
                echo '</div>';
            } else
                echo '
                    <div class="alert alert-secondary text-break">
                        <span>' . __d('npds', 'Vous pouvez charger un fichier carnet.txt dans votre miniSite') . '.</span><br />
                        <span>' . __d('npds', 'La structure de chaque ligne de ce fichier : nom_du_membre; adresse Email; commentaires') . '</span>
                    </div>';
        
            echo '
            </body>
            </html>';
        }        
    }

    function L_encrypt($txt)
    {
        global $userdata;
    
        $key = substr($userdata[2], 8, 8);
    
        return (encryptK($txt, $key));
    }

}