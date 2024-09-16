<?php

namespace App\Modules\Npds\Library;

use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Contracts\EmoticoneInterface;



class EmoticoneManager implements EmoticoneInterface 
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
     * [smilie description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    public function smilie($message)
    {
        // Tranforme un :-) en IMG
        global $theme;
    
        if ($ibid = Theme::theme_image("forum/smilies/smilies.php")) {
            $imgtmp = "$theme/assets/images/forum/smilies/";
            $imgurl = "themes/$theme/assets/images/forum/smilies/";
        } else {
            $imgtmp = "assets/images/forum/smilies/";
            $imgurl = "assets/images/forum/smilies/";
        }

        if (file_exists(theme_path($imgtmp . "smilies.php"))) {
    
            include(theme_path($imgtmp . "smilies.php"));
    
            foreach ($smilies as $tab_smilies) {
                $suffix = strtoLower(substr(strrchr($tab_smilies[1], '.'), 1));
    
                if (($suffix == "gif") or ($suffix == "png"))
                    $message = str_replace($tab_smilies[0], "<img class='n-smil' src='" . site_url($imgurl . $tab_smilies[1]) . "' loading='lazy' />", $message);
                else
                    $message = str_replace($tab_smilies[0], $tab_smilies[1], $message);
            }
        }
    
        if ($ibid = Theme::theme_image("forum/smilies/more/smilies.php")) {
            $imgtmp = "$theme/assets/images/forum/smilies/more/";
            $imgurl = "theme/$theme/assets/images/forum/smilies/more/";
        } else {
            $imgtmp = "assets/images/forum/smilies/more/";
            $imgurl = "assets/images/forum/smilies/more/";
        }

        if (file_exists(theme_path($imgtmp . "smilies.php"))) {
            include(theme_path($imgtmp . "smilies.php"));
    
            foreach ($smilies as $tab_smilies) {
                $message = str_replace($tab_smilies[0], "<img class='n-smil' src='" . site_url($imgurl . $tab_smilies[1]) . "' loading='lazy' />", $message);
            }
        }
    
        return ($message);
    }
    
    /**
     * [smile description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    public function smile($message)
    {
        // Tranforme une IMG en :-)
        global $theme;
    
        if ($ibid = theme_image("forum/smilies/smilies.php")) {
            $imgtmp = "themes/$theme/assets/images/forum/smilies/";
        } else {
            $imgtmp = "assets/images/forum/smilies/";
        }
    
        if (file_exists($imgtmp . "smilies.php")) {
            include($imgtmp . "smilies.php");
    
            foreach ($smilies as $tab_smilies) {
                $message = str_replace("<img class='n-smil' src='" . $imgtmp . $tab_smilies[1] . "' loading='lazy' />", $tab_smilies[0], $message);
            }
        }
    
        if ($ibid = theme_image("forum/smilies/more/smilies.php")) {
            $imgtmp = "themes/$theme/assets/images/forum/smilies/more/";
        } else {
            $imgtmp = "assets/images/forum/smilies/more/";
        }
    
        if (file_exists($imgtmp . "smilies.php")) {
            include($imgtmp . "smilies.php");
    
            foreach ($smilies as $tab_smilies) {
                $message = str_replace("<img class='n-smil' src='" . $imgtmp . $tab_smilies[1] . "' loading='lazy' />", $tab_smilies[0],  $message);
            }
        }
    
        return ($message);
    }

    /**
     * [putitems_more description]
     *
     * @return  [type]  [return description]
     */
    public function putitems_more()
    {
        global $theme, $tmp_theme;
    
        if (stristr($_SERVER['PHP_SELF'], "more_emoticon.php")) 
            $theme = $tmp_theme;
    
        echo '<p align="center">' . translate("Cliquez pour insérer des émoticons dans votre message") . '</p>';
    
        if ($ibid = theme_image("forum/smilies/more/smilies.php")) {
            $imgtmp = "themes/$theme/assets/images/forum/smilies/more/";
        } else {
            $imgtmp = "assets/images/forum/smilies/more/";
        }
    
        if (file_exists($imgtmp . "smilies.php")) {
            include($imgtmp . "smilies.php");
    
            echo '<div>';
    
            foreach ($smilies as $tab_smilies) {
                if ($tab_smilies[3]) {
                    echo '<span class ="d-inline-block m-2"><a href="#" onclick="javascript: DoAdd(\'true\',\'message\',\' ' . $tab_smilies[0] . '\');"><img src="' . $imgtmp . $tab_smilies[1] . '" width="32" height="32" alt="' . $tab_smilies[2];
                    
                    if ($tab_smilies[2]) 
                        echo ' => ';
    
                    echo $tab_smilies[0] . '" loading="lazy" /></a></span>';
                }
            }
    
            echo '</div>';
        }
    }
    
    /**
     * [putitems description]
     *
     * @param   [type]  $targetarea  [$targetarea description]
     *
     * @return  [type]               [return description]
     */
    public function putitems($targetarea)
    {
        global $theme;
    
        echo '
        <div title="' . translate("Cliquez pour insérer des emoji dans votre message") . '" data-bs-toggle="tooltip">
            <button class="btn btn-link ps-0" type="button" id="button-textOne" data-bs-toggle="emojiPopper" data-bs-target="#' . $targetarea . '">
                <i class="far fa-smile fa-lg" aria-hidden="true"></i>
            </button>
        </div>
        <script src="assets/shared/emojipopper/js/emojiPopper.min.js"></script>
        <script type="text/javascript">
        //<![CDATA[
            $(function () {
                "use strict"
                var emojiPopper = $(\'[data-bs-toggle="emojiPopper"]\').emojiPopper({
                    url: "assets/shared/emojipopper/php/emojicontroller.php",
                    title:"Choisir un emoji"
                });
            });
        //]]>
        </script>';
    }

}
