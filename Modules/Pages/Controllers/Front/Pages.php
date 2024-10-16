<?php

namespace Modules\Pages\Controllers\Front;

use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;


class Pages extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


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
    public function index()
    {  
        $remp = '';
        
        echo '<div id="static_cont">';
        
        if (($op != '') and ($op)) {
            // Troll Control for security
            if (preg_match('#^[a-z0-9_\.-]#i', $op) 
            and !stristr($op, ".*://") 
            and !stristr($op, "..") 
            and !stristr($op, "../") 
            and !stristr($op, "script") 
            and !stristr($op, "cookie") 
            and !stristr($op, "iframe") 
            and  !stristr($op, "applet") 
            and !stristr($op, "object") 
            and !stristr($op, "meta")) {

                if (file_exists("storage/static/$op")) {
                    
                    if (!$metalang and !$nl) {
                        include("storage/static/$op");
                        
                    } else {
                        ob_start();
                            include("storage/static/$op");
                            $remp = ob_get_contents();
                        ob_end_clean();
        
                        if ($metalang) {
                            $remp = Metalang::meta_lang(Code::aff_code(Language::aff_langue($remp)));
                        }
        
                        if ($nl) {
                            $remp = nl2br(str_replace(' ', '&nbsp;', htmlentities($remp, ENT_QUOTES, cur_charset)));
                        }

                        echo $remp;
                    }
        
                    echo '<div class=" my-3">
                        <a href="print.php?sid=static:' . $op . '&amp;metalang=' . $metalang . '&amp;nl=' . $nl . '" data-bs-toggle="tooltip" data-bs-placement="right" title="' . __d('pages', 'Page spéciale pour impression') . '">
                            <i class="fa fa-2x fa-print"></i>
                        </a>
                    </div>';
        
                    // Si vous voulez tracer les appels au pages statiques : supprimer les // devant la ligne ci-dessous
                    // Ecr_Log("security", "static/$op", "");
                } else {
                    echo '<div class="alert alert-danger">
                        ' . __d('pages', 'Merci d\'entrer l\'information en fonction des spécifications') . '
                    </div>';
                }
            } else {
                echo '<div class="alert alert-danger">
                    ' . __d('pages', 'Merci d\'entrer l\'information en fonction des spécifications') . '
                </div>';
            }
        }
        
        echo '</div>';
    }

}
