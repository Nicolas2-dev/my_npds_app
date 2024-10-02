<?php

namespace App\Modules\Users\Library;

use Npds\Http\Request;
use Npds\Config\Config;
use App\Modules\Upload\Library\Upload;
use App\Modules\Npds\Support\Facades\Hack;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Users\Contracts\AvatarInterface;

/**
 * Undocumented class
 */
class AvatarManager implements AvatarInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * Undocumented function
     *
     * @param [type] $input
     * @return void
     */
    public function user_avatar_update($input)
    {
        $user_avatar    = Request::post('user_avatar', false);
        $raz_avatar     = Request::post('raz_avatar', false);
        
        if (!Request::post('B1', false)) {

            $rep = !empty($DOCUMENTROOT = Config::get('upload.config.DOCUMENTROOT')) ? $DOCUMENTROOT : $_SERVER['DOCUMENT_ROOT'];

            $upload = new Upload();

            $upload->maxupload_size = $input['MAX_FILE_SIZE'];
            $field1_filename = trim($upload->getFileName("B1"));

            $suffix = strtoLower(substr(strrchr($field1_filename, '.'), 1));

            $racine = Config::get('upload.config.racine');

            $user_dir = $racine . 'app/Modules/Users/storage/users_private/' . $input['uname'] . '/';

            if (($suffix == 'gif') or ($suffix == 'jpg') or ($suffix == 'png') or ($suffix == 'jpeg')) {

                $field1_filename = Hack::remove(preg_replace('#[/\\\:\*\?"<>|]#i', '', rawurldecode($field1_filename)));
                $field1_filename = preg_replace('#\.{2}|config.php|/etc#i', '', $field1_filename);

                if ($field1_filename) {
                    if (Config::get('upload.config.autorise_upload_p')) {

                        if (!is_dir($rep . $user_dir)) {
                            @umask("0000");

                            if (@mkdir($rep . $user_dir, 0777)) {
                                $fp = fopen($rep . $user_dir . 'index.html', 'w');
                                fclose($fp);
                            } else {
                                $user_dir = $racine . 'app/Modules/Users/storage/users_private/';
                            }
                        }
                    } else {
                        $user_dir = $racine . 'app/Modules/Users/storage/users_private/';
                    }

                    if ($upload->saveAs($input['uname'] . '.' . $suffix, $rep . $user_dir, 'B1', true)) {

                        $user_avatar_url    = 'modules/users/storage/users_private/' . $input['uname'] . '/';
                        $user_avatar        = site_url($user_avatar_url . $input['uname'] . '.' . $suffix);
                        $img_size           = @getimagesize($rep . $user_dir . $input['uname'] . '.' . $suffix);

                        $avatar_limit       = explode("*", Config::get('npds.avatar_size', '80*100'));

                        if (($img_size[0] > $avatar_limit[0]) or ($img_size[1] > $avatar_limit[1])) {
                            $raz_avatar = true;
                        }
                    }
                }
            }
        }

        if ($raz_avatar) {

            if (strstr($user_avatar, '/users_private')) {
                if(!empty($user_avatar)) {
                    @unlink($rep. $user_dir. $input['uname'] . '.' . strtoLower(substr(strrchr($user_avatar, '.'), 1)));
                }
            }

            $user_avatar = 'blank.gif';
        }
        
        return $user_avatar;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function url($temp_user)
    {
        $avatar_url  = site_url('assets/images/forum/avatar/' . $temp_user['user_avatar']);

        if (stristr($temp_user['user_avatar'], 'users_private')) {
            $avatar_url = $temp_user['user_avatar'];
        } else {
            if (method_exists(Theme::class, 'theme_image_row')) {
                $avatar_url = Theme::theme_image_row('images/forum/avatar/' . $temp_user['user_avatar']);
            }
        }

        return $avatar_url;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function directory()
    {
        $theme      = with(get_instance())->template();
        $theme_dir  = with(get_instance())->template_dir();

        $path = web_path('assets/images/forum/avatar');
        $url  = site_url('assets/images/forum/avatar');

        if (method_exists(Theme::class, 'theme_image')) {
            if (Theme::theme_image('forum/avatar/blank.gif')) {
                $path = theme_path($theme .'/assets/images/forum/avatar');
                $url  = site_url('themes/'. $theme_dir .'/'. $theme .'/assets/images/forum/avatar');
            }
        }

        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            $filelist[] = $file;
        }

        asort($filelist);

        return [$filelist, $url];
    }

}
