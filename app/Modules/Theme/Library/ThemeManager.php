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

/**
 * Undocumented class
 */
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
        $this->theme      = with(get_instance()->template());       
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
    public function lists($dir, $explode = false)
    {
        $handle = opendir(theme_path($dir));

        while (false !== ($file = readdir($handle))) {
            if (($file[0] !== '_') and (!strstr($file, '.'))) {
                $themelist[] = $file;
            }
        }
        
        natcasesort($themelist);
        
        if ($explode) {
            $themelist = implode(' ', $themelist);
        }

        closedir($handle);   
        
        return $themelist;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function skin_lists()
    {
        $handle = opendir(web_path('assets/skins'));
        
        while (false !== ($file = readdir($handle))) {
            if (($file[0] !== '_') 
            and (!strstr($file, '.')))  
            {
                $skins[] = array('name'         => $file, 
                                'description'   => '', 
                                'thumbnail'     => $file . '/thumbnail', 
                                'preview'       => $file . '/', 
                                'css'           => $file . '/bootstrap.css', 
                                'cssMin'        => $file . '/bootstrap.min.css', 
                                'cssxtra'       => $file . '/extra.css', 
                                'scss'          => $file . '/_bootswatch.scss', 
                                'scssVariables' => $file . '/_variables.scss');
            }
        }
    
        closedir($handle);
    
        asort($skins);
        
        return $skins;
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
    public function theme_image($theme_img,)
    {
        $theme      = with(get_instance())->template();
        $theme_dir  = with(get_instance())->template_dir();

        if (@file_exists(theme_path($theme_dir .'/'. $theme .'/assets/images/'. $theme_img))) {
            return site_url('themes/'. $theme_dir .'/'. $theme .'/assets/images/'. $theme_img);
        } else {
            return false;
        }
    }

    /**
     * [theme_image description]
     *
     * @param   [type]  $theme_img  [$theme_img description]
     *
     * @return  [type]              [return description]
     */
    public function theme_image_row($theme_img, $package = '')
    {
        $instance   = get_instance();
        $theme      = $instance->template();
        $theme_dir  = $instance->template_dir();

        if (@file_exists(theme_path($theme_dir .'/'. $theme .'/assets/images/'. $theme_img))) {
            return site_url('themes/' .strtolower($theme_dir) .'/'. strtolower($theme) .'/assets/images/'.$theme_img);
        
        } elseif (@file_exists(module_path($package .'/assets/images/'. $theme_img)) and ($package != '')) {
            return site_url('modules/'. $package .'/assets/images/'.$theme_img);
        
        } elseif (@file_exists(web_path('assets/images/'. $theme_img))) {
            return site_url('assets/images/'.$theme_img);

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
