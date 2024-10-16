<?php

namespace Modules\Upload\Controllers\Api;

use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
use Modules\Upload\Support\Facades\NpdsUpload;


class UploadEditeur extends FrontController
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

    public function index()
    {
        $Titlesitename = __d('upload', 'Télécharg.');

        include("storage/meta/meta.php");

        if ($url_upload_css) {
            $url_upload_cssX = str_replace('style.css', Config::get('npds.language') ."-style.css", $url_upload_css);

            if (is_readable($url_upload . $url_upload_cssX)) {
                $url_upload_css = $url_upload_cssX;
            }

            print("<link href=\"" . $url_upload . $url_upload_css . "\" title=\"default\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n");
        }

        echo "</head>\n";

        if (isset($actiontype)) {

            switch ($actiontype) {

                case 'upload':
                    $ret = NpdsUpload::editeur_upload();
                    $js = '';

                    if ($ret != '') {
                        $suffix = strtoLower(substr(strrchr($ret, '.'), 1));

                        if ($suffix == 'gif' or  $suffix == 'jpg' or  $suffix == 'jpeg' or $suffix == 'png') {
                            $js .= "parent.tinymce.activeEditor.selection.setContent('<img class=\"img-fluid\" src=\"$ret\" alt=" . basename($ret) . " loading=\"lazy\" />');";
                        } else {
                            $js .= "parent.tinymce.activeEditor.selection.setContent('<a href=\"$ret\" target=\"_blank\">" . basename($ret) . "</a>');";
                        }
                    }

                    echo "<script type=\"text/javascript\">
                        //<![CDATA[
                        " . $js . "
                        top.tinymce.activeEditor.windowManager.close();
                        //]]>
                        </script>";
                    die();
                    break;
            }
        }

        echo '
            <body topmargin="3" leftmargin="3" rightmargin="3">
                <div class="card card-body mx-2 mt-3">
                    <form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data" name="formEdit">
                        <input type="hidden" name="apli" value="' . $apli . '" />';

        if (isset($groupe)) {
            echo '<input type="hidden" name="groupe" value="' . $groupe . '" />';
        }

        echo '
                        <div class="mb-3 row">
                        <input type="hidden" name="actiontype" value="upload" />
                        <label class="form-label">' . __d('upload', 'Fichier') . '</label>
                        <input class="form-control" name="pcfile" type="file" id="pcfile" value="" />
                        </div>
                        <div class="mb-3 row">
                        <input type="submit" class="btn btn-primary btn-sm" name="insert" value="' . __d('upload', 'Joindre') . '" />
                        </div>
                    </form>
                </div>
            </body>
        </html>';        
    }

}
