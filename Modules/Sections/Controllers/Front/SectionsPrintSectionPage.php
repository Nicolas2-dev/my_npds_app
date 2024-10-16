<?php

namespace Modules\Sections\Controllers\Front;

use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Code;
use Modules\Npds\Support\Facades\Language;
use Modules\Npds\Support\Facades\Metalang;
use Modules\Sections\Support\Facades\Section;


class SectionsPrintSectionPage extends FrontController
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
     * @param [type] $artid
     * @return void
     */
    public function PrintSecPage($artid)
    {
        if (Section::verif_aff($artid)) {

            include("storage/meta/meta.php");
        
            echo '
                    <link rel="stylesheet" href="assets/shared/bootstrap/dist/css/bootstrap.min.css" />
                </head>
                <body>
                    <div id="print_sect" max-width="640" class="container p-1 n-hyphenate">
                        <p class="text-center">';
        
            $pos = strpos(Config::get('npds.site_logo'), "/");
        
            echo $pos 
                ? '<img src="' . Config::get('npds.site_logo') . '" alt="logo" />' 
                : '<img src="assets/images/' . Config::get('npds.site_logo') . '" alt="logo" />';
        
            $result = sql_query("SELECT title, content FROM seccont WHERE artid='$artid'");
            list($title, $content) = sql_fetch_row($result);
        
            echo '<strong class="my-3 d-block">' . Language::aff_langue($title) . '</strong></p>';
        
            $content    = Code::aff_code(Language::aff_langue($content));
            $pos_page   = strpos($content, "[page");
        
            if ($pos_page) {
                $content = str_replace("[page", str_repeat("-", 50) . "&nbsp;[page", $content);
            }
        
            echo Metalang::meta_lang($content);
        
            echo '
                    <hr />
                    <p class="text-center">
                    ' . __d('sections', 'Cet article provient de') . ' ' . Config::get('npds.sitename') . '<br /><br />
                    ' . __d('sections', 'L\'url pour cet article est : ') . '
                    <a href="' . Config::get('npds.nuke_url') . '/sections.php?op=viewarticle&amp;artid=' . $artid . '">' . Config::get('npds.nuke_url') . '/sections.php?op=viewarticle&amp;artid=' . $artid . '</a>
                    </p>
                    </div>
                    <script type="text/javascript" src="assets/shared/jquery/jquery.min.js"></script>
                    <script type="text/javascript" src="assets/shared/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
                    <script type="text/javascript" src="assets/js/npds_adapt.js"></script>
                </body>
            </html>';
        } else {
            header('location: sections.php');
        }          
    }

}
