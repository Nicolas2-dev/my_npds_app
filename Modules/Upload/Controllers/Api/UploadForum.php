<?php

namespace Modules\Upload\Controllers\Api;


use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Auth;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Upload\Support\Facades\NpdsUpload;
use Modules\Upload\Support\Traits\UploadMinegfTrait;


class UploadForum extends FrontController
{

    use UploadMinegfTrait;

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
        // Security
        if (!Config::get('forum.config.allow_upload_forum')) {
            Access_Error();
        }

        if (!autorize()) {
            Access_Error();
        }

        //
        $this->upload_head();

        //
        $this->forum_moderateur();

        //
        $this->upload_action_type();

        // 
        $this->minigf();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function upload_head()
    {
        // Entete
        ob_start();

        $Titlesitename = __d('upload', 'Télécharghargement.');

        include("storage/meta/meta.php");

        $user = Auth::check('user');

        $userX      = base64_decode($user);
        $userdata   = explode(':', $userX);

        if ($userdata[9] != '') {
            if (!@opendir(theme_path($userdata[9]))) {
                $theme = Config::get('npds.Default_Theme');
            } else {
                $theme = $userdata[9];
            }
        } else {
            $theme = Config::get('npds.Default_Theme');
        }

        if (isset($user)) {
            $cookie = Cookie::cookiedecode($user);
            
            $skin = '';

            if (array_key_exists(11, $cookie)) {
                $skin = $cookie[11];
            }
        }

        echo '<link rel="stylesheet" href="'. site_url('assets/shared/font-awesome/css/all.min.css') .'" />';

        if ($skin != '') {
            echo '
                <link rel="stylesheet" href="'. site_url('assets/skins/' . $skin . '/bootstrap.min.css') .'" />
                <link rel="stylesheet" href="'. site_url('assets/skins/' . $skin . '/extra.css') .'" />';
        } else {
            echo '<link rel="stylesheet" href="'. site_url('assets/shared/bootstrap/dist/css/bootstrap.min.css') .'" />';
        }

        echo '<link rel="stylesheet" href="'. site_url('assets/shared/bootstrap-table/dist/bootstrap-table.min.css') .'" />';

        echo Css::import_css($theme, '', '', '');

        echo '
            </head>
        <body>';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function upload_action_type()
    {
        //
        if ($actiontype = Request::post('actiontype')) {

            switch ($actiontype) {
                case 'delete':
                    NpdsUpload::delete(request::post('del_att'));
                    break;

                case 'upload':
                    $thanks_msg = NpdsUpload::forum_upload();
                    $this->set_thanks_msg($thanks_msg);
                    break;

                case 'update':
                    NpdsUpload::update_inline(request::post('inline_att'));
                    break;

                case 'visible':

                    if ($this->get_mod()) {
                        NpdsUpload::update_visibilite(request::post('visible_att'), request::post('visible_list'));
                    }
                    break;
            }
        }
    }

}
