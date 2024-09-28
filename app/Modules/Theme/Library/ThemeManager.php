<?php

namespace App\Modules\Theme\Library;

use Npds\Config\Config;
use App\Modules\Npds\Support\Facades\Metalang;
use App\Modules\Theme\Contracts\ThemeInterface;
use App\Modules\Theme\Library\Traits\ThemeHeadTrait;
use App\Modules\News\Library\Traits\ThemeStorieTrait;
use App\Modules\Theme\Library\Traits\ThemeFooterTrait;
use App\Modules\Theme\Library\Traits\ThemeHeaderTrait;
use App\Modules\Blocks\Library\Traits\ThemeSideboxTrait;
use App\Modules\Theme\Library\Traits\ThemeOnloadBodyTrait;


class ThemeManager implements ThemeInterface 
{

    use ThemeHeadTrait, ThemeSideboxTrait, ThemeHeaderTrait, ThemeFooterTrait, ThemeStorieTrait, ThemeOnloadBodyTrait;

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;

    /**
     * [$theme description]
     *
     * @var [type]
     */
    protected $theme;

    /**
     * [__construc description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        $this->theme = with(get_instance())->template();
    }

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
     * [lists description]
     *
     * @return  [type]  [return description]
     */
    public function lists()
    {
        $handle = opendir(theme_path());

        while (false !== ($file = readdir($handle))) {
            if (($file[0] !== '_') 
            and (!strstr($file, '.')) 
            //and (!strstr($file, 'themes-dynamic')) 
            //and (!strstr($file, 'documentations')) 
            and (!strstr($file, 'Default'))
            and (!strstr($file, 'Backend'))
            and (!strstr($file, 'default_')))
                $themelist[] = $file;
        }
        
        natcasesort($themelist);
        
        $themelist = implode(' ', $themelist);
        closedir($handle);   
        
        return $themelist;
    }

    /**
     * [config description]
     *
     * @param   [type]  $key  [$key description]
     *
     * @return  [type]        [return description]
     */
    public function config($key, $default = '')
    {
        $theme = with(get_instance())->template();

        return Config::get(strtolower($theme) .'.'. $key, $default);
    }

    /**
     * [theme_image description]
     *
     * @param   [type]  $theme_img  [$theme_img description]
     *
     * @return  [type]              [return description]
     */
    public function theme_image($theme_img)
    {
        $theme = with(get_instance())->template();

        if (@file_exists(theme_path($theme .'/assets/images/'. $theme_img))) {
            return site_url('themes/'. $theme .'/assets/images/'. $theme_img);
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    function showimage()
    {
        echo "
        <script type=\"text/javascript\">
        //<![CDATA[
        function showimage() {
        if (!document.images)
            return
            document.images.avatar.src=\n";
    
        if ($ibid = theme_image("forum/avatar/blank.gif"))
            $imgtmp = substr($ibid, 0, strrpos($ibid, "/") + 1);
        else
            $imgtmp = "assets/images/forum/avatar/";
    
        echo "'". site_url($imgtmp) ."' + document.Register.user_avatar.options[document.Register.user_avatar.selectedIndex].value\n";
    
        echo "}
        //]]>
        </script>";
    }

    /**
     * [themepreview description]
     *
     * @param   [type]  $title     [$title description]
     * @param   [type]  $hometext  [$hometext description]
     * @param   [type]  $bodytext  [$bodytext description]
     * @param   [type]  $notes     [$notes description]
     *
     * @return  [type]             [return description]
     */
    public function themepreview($title, $hometext, $bodytext = '', $notes = '')
    {
        echo "$title<br />" . Metalang::meta_lang($hometext) . "<br />" . Metalang::meta_lang($bodytext) . "<br />" . Metalang::meta_lang($notes);
    }

    /**
     * [theme_path description]
     *
     * @return  [type]  [return description]
     */
    public function theme_path()
    {
        $path = realpath(app_path('Themes/'));

        return rtrim($path, '/\\');
    }

    /**
     * [colsyst description]
     *
     * @param   [type]  $coltarget  [$coltarget description]
     *
     * @return  [type]              [return description]
     */
    public function colsyst($coltarget)
    {
        return '
            <div class="col d-lg-none me-2 my-2">
                <hr />
                <a class=" small float-end" href="#" data-bs-toggle="collapse" data-bs-target="' . $coltarget . '"><span class="plusdecontenu trn">Plus de contenu</span></a>
            </div>';
    }

    /**
     * [local_var description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function local_var($Xcontent)
    {
        if (strstr($Xcontent, "!var!")) {
            $deb = strpos($Xcontent, "!var!", 0) + 5;
            $fin = strpos($Xcontent, ' ', $deb);
    
            if ($fin) {
                $H_var = substr($Xcontent, $deb, $fin - $deb);
            } else {
                $H_var = substr($Xcontent, $deb);
            }
    
            return $H_var;
        }
    }

}
