<?php

namespace Modules\Upload\Controllers\Api;

use Npds\Http\Request;
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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        // 
        $this->upload_head();

        //
        $this->upload_action_type();

        //
        $apli = Request::post('apli');

        // code a placer dans une vue  !
        echo '
            <body topmargin="3" leftmargin="3" rightmargin="3">
                <div class="card card-body mx-2 mt-3">
                    <form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data" name="formEdit">
                        <input type="hidden" name="apli" value="' . $apli . '" />';

        if (isset($groupe)) {
            dump($groupe); // pour comprendre de ou vien ce groupe
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

    /**
     * Undocumented function
     *
     * @return void
     */
    private function upload_head()
    {
        $Titlesitename = __d('upload', 'Téléchargargement.');

        include("storage/meta/meta.php");

        $url_upload_css = Config::get('upload.url_upload_css');
        $url_upload     = Config::get('upload.url_upload');

        if ($url_upload_css) {
            $url_upload_cssX = str_replace('style.css', Config::get('npds.language') ."-style.css", $url_upload_css);

            if (is_readable($url_upload . $url_upload_cssX)) {
                $url_upload_css = $url_upload_cssX;
            }

            print('<link href="' . site_url($url_upload . $url_upload_css) . '" title="default" rel="stylesheet" type="text/css" media="all" />'. "\n");
        }

        echo '</head>' ."\n";
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function upload_action_type()
    {
        $actiontype = Request::post('actiontype');

        if (isset($actiontype)) {

            if ($actiontype == 'upload') {

                $ret = NpdsUpload::editeur_upload();

                if ($ret != '') {
                    $suffix = strtoLower(substr(strrchr($ret, '.'), 1));

                    if ($suffix == 'gif' 
                    or  $suffix == 'jpg' 
                    or  $suffix == 'jpeg' 
                    or $suffix == 'png') {
                        $js = '<img class="img-fluid" src="'. $ret .'" alt="' . basename($ret) . '" loading="lazy" />';
                    } else {
                        $js = '<a href="'. $ret .'" target="_blank">' . basename($ret) . '</a>';
                    }
                }

                echo '<script type="text/javascript">
                        //<![CDATA[
                            parent.tinymce.activeEditor.selection.setContent("' . $js . '")
                            top.tinymce.activeEditor.windowManager.close();
                         //]]>
                    </script>';

                return true;
            }
        }        
    }

}
