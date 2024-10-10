<?php

namespace App\Controllers\Front;

use App\Controllers\Core\FrontController;


class FrontAutodoc extends FrontController
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
        settype($op, 'string');

        if ($op == 'blocs') {
            dochead('mainfile.php', 'powerpack_f.php');
            docu();
        }
        
        if ($op == 'main') {
            dochead('mainfile.php', '');
            autodoc('mainfile.php', '');
            docfoot();
        }
        
        if ($op == 'func') {
            dochead('functions.php', '');
            autodoc('functions.php', '');
            docfoot();
        }        
    }

    function Access_Error()
    {
        include("admin/die.php");
    }
    
    function dochead($a, $b)
    {
        if (file_exists("storage/meta/meta.php")) {
            $Titlesitename = "Npds - Doc";
    
            include("storage/meta/meta.php");
            include("themes/default/include/header_head.inc");
    
            echo '
            </head>
            <body class="my-3 mx-3">
                <h1 class="mb-3">Documentation des fonctions App</h1>
                <p class="h4 my-3"><i class="me-1 far fa-file-alt"></i>' . $a . ' ' . $b . ' <span class="text-muted">[ Documentation ]</span></p>';
        }
    }
    
    function docfoot()
    {
        echo '
            <p class="text-end small my-3 text-muted">Autodoc by App</p>
        </body>
        </html>';
    }
    
    function autodoc($fichier, $paragraphe)
    {
        $fcontents = @file($fichier);
    
        if ($fcontents == '')
            Access_Error();
    
        $pasfin = false;
        $tabdoc = '';
    
        echo '
            <table class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>Fonction</th>
                    <th>Documentation</th>
                </tr>
                </thead>
                <tbody>';
    
        foreach ($fcontents as $line_num => $line) {
            if ($paragraphe != '') {
                if (strstr($line, "#autodoc:<$paragraphe>")) {
                    $line = '';
                    $pasfin = true;
                }
    
                if (strstr($line, "#autodoc:</$paragraphe>")) {
                    $line = '';
                    $pasfin = false;
                }
            } else
                $pasfin = true;
    
            $line = trim($line);
    
            if ((strstr($line, '#autodoc')) and ($pasfin)) {
                $posX = strpos($line, ':');
                $morceau1 = trim(substr($line, strpos($line, "#autodoc") + 8, $posX - 8));
                $morceau2 = rtrim(substr($line, $posX + 1));
    
                $tabdoc .= '
                <tr>
                   <td><code>' . $morceau1 . '</code></td>
                   <td>' . $morceau2 . '</td>
                </tr>';
            } else if ((strstr($line, '# autodoc')) and ($pasfin)) {
                $posX = strpos($line, ':');
                $morceau1 = ltrim(substr($line, strpos($line, '# autodoc') + 9, $posX - 9));
                $morceau2 = rtrim(substr($line, $posX + 1));
    
                $tabdoc .= '
                <tr>
                   <td nowrap="nowrap"><code>' . $morceau1 . '</code></td>
                   <td>' . $morceau2 . '</td>
                </tr>';
            }
        }
    
        echo $tabdoc;
    
        echo '
             </tbody>
          </table>';
    }
    
    function docu()
    {
        echo '
          <p class="h5 my-3">Mainfile.php</p>';
        autodoc("mainfile.php", "Mainfile.php");
    
        echo '
          <p class="h5 my-3">Powerpack_f.php</p>';
        autodoc("powerpack_f.php", "Powerpack_f.php");
    
        echo '
            <div class="alert alert-success mt-3">Rappels :<br />Si votre thème est adapté, chaque bloc peut contenir :<br />- class-title#nom de la classe de la CSS pour le titre du bloc<br />- class-content#nom de la classe de la CSS pour le corps du bloc<br />- uri#uris séparée par un espace</div>
            <p class="text-end small my-3 text-muted">Autodoc by App</p>
        </body>
        </html>';
    
        die();
    }

}