<?php

namespace Modules\Upload\Controllers\Api;


use Npds\Config\Config;
use Modules\Npds\Core\FrontController;
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
        $forum = $IdForum;

        $inline_list['1'] = __d('upload', 'Oui');
        $inline_list['0'] = __d('upload', 'Non');

        // Security
        if (!$allow_upload_forum) {
            Access_Error();
        }

        if (!autorize()) {
            Access_Error();
        }

        // Entete

        ob_start();

        $Titlesitename = __d('upload', 'Télécharg.');

        include("storage/meta/meta.php");

        $userX = base64_decode($user);
        $userdata = explode(':', $userX);

        if ($userdata[9] != '') {
            if (!$file = @opendir("themes/$userdata[9]")) {
                $theme = Config::get('npds.Default_Theme');
            } else {
                $theme = $userdata[9];
            }
        } else {
            $theme = Config::get('npds.Default_Theme');
        }

        if (isset($user)) {
            global $cookie;
            $skin = '';

            if (array_key_exists(11, $cookie)) {
                $skin = $cookie[11];
            }
        }

        echo '<link rel="stylesheet" href="assets/shared/font-awesome/css/all.min.css" />';

        if ($skin != '') {
            echo '
                <link rel="stylesheet" href="assets/skins/' . $skin . '/bootstrap.min.css" />
                <link rel="stylesheet" href="assets/skins/' . $skin . '/extra.css" />';
        } else {
            echo '<link rel="stylesheet" href="assets/shared/bootstrap/dist/css/bootstrap.min.css" />';
        }

        echo '<link rel="stylesheet" href="assets/shared/bootstrap-table/dist/bootstrap-table.min.css" />'; //hardcoded lol

        echo import_css($theme, '', '', '');

        echo '
            </head>
        <body>';

        // Moderator

        $sql = "SELECT forum_moderator FROM forums WHERE forum_id = '$forum'";
        if (!$result = sql_query($sql)) {
            forumerror('0001');
        }

        $myrow      = sql_fetch_assoc($result);
        $moderator  = get_moderator($myrow['forum_moderator']);
        $moderator  = explode(' ', $moderator);

        $Mmod = false;

        for ($i = 0; $i < count($moderator); $i++) {
            if (($userdata[1] == $moderator[$i])) {
                $Mmod = true;
                break;
            }
        }

        $thanks_msg = '';

        //settype($thanks_msg,'string');
        settype($actiontype, 'string');
        settype($visible_att, 'array');

        if ($actiontype) {

            switch ($actiontype) {
                case 'delete':
                    NpdsUpload::delete($del_att);
                    break;

                case 'upload':
                    $thanks_msg = NpdsUpload::forum_upload();
                    break;

                case 'update':
                    NpdsUpload::update_inline($inline_att);
                    break;

                case 'visible':
                    if ($Mmod) {
                        NpdsUpload::update_visibilite($visible_att, $visible_list);
                    }
                    break;
            }
        }
    }

}
