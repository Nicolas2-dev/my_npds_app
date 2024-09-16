<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Users\Support\Facades\User;
use App\Modules\Npds\Support\Facades\Cookie;
use App\Modules\Npds\Library\Supercache\SuperCacheManager;

/**
 * [UserLogin description]
 */
class UserTheme extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'chgtheme':
        //         if ($user)
        //             chgtheme();
        //         else
        //             Header("Location: index.php");
        //         break;
        
        //     case 'savetheme':
        //         $theme = substr($theme_local, -3) != "_sk" ? $theme_local : $theme_local . "+" . $skins;
        //         savetheme($uid, $theme);
        //         break;        
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
     * [chgtheme description]
     *
     * @return  [type]  [return description]
     */
    public function chgtheme()
    {
        global $user;
    
        $userinfo = User::getusrinfo($user);
        
        $ibid = explode('+', $userinfo['theme']);
        $theme = $ibid[0];
    
        if (array_key_exists(1, $ibid)) {
            $skin = $ibid[1];
        } else {
            $skin = '';
        }
    
        member_menu($userinfo['mns'], $userinfo['uname']);
    
        echo '
        <h2 class="mb-3">' . translate("Changer le thème") . '</h2>
        <form action="user.php" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 form-floating">
                    <select class="form-select" id="theme_local" name="theme_local">';
    
        include("themes/list.php");
    
        $themelist = explode(' ', $themelist);
        $thl = sizeof($themelist);
    
        for ($i = 0; $i < $thl; $i++) {
            if ($themelist[$i] != '') {
                echo '<option value="' . $themelist[$i] . '" ';
    
                if ((($theme == '') && ($themelist[$i] == Config::get('npds.Default_Theme'))) || ($theme == $themelist[$i])) {
                    echo 'selected="selected"';
                }
    
                echo '>' . $themelist[$i] . '</option>';
            }
        }
    
        echo '
                    </select>
                    <label for="theme_local">' . translate("Sélectionnez un thème d'affichage") . '</label>
                    </div>
                    <p class="help-block mb-4">
                    <span>' . translate("Cette option changera l'aspect du site.") . '</span> 
                    <span>' . translate("Les modifications seront seulement valides pour vous.") . '</span> 
                    <span>' . translate("Chaque utilisateur peut voir le site avec un thème graphique différent.") . '</span>
                    </p>';
    
        $handle = opendir('assets/skins');
    
        while (false !== ($file = readdir($handle))) {
            if (($file[0] !== '_') 
            and (!strstr($file, '.')) 
            and (!strstr($file, 'assets'))
            and (!strstr($file, 'fonts'))) 
            {
                $skins[] = array('name' => $file, 
                                 'description' => '', 
                                 'thumbnail' => $file . '/thumbnail', 
                                 'preview' => $file . '/', 
                                 'css' => $file . '/bootstrap.css', 
                                 'cssMin' => $file . '/bootstrap.min.css', 
                                 'cssxtra' => $file . '/extra.css', 
                                 'scss' => $file . '/_bootswatch.scss', 
                                 'scssVariables' => $file . '/_variables.scss');
            }
        }
    
        closedir($handle);
    
        asort($skins);
    
        echo '
                    <div class="mb-3 form-floating" id="skin_choice">
                    <select class="form-select" id="skins" name="skins">';
    
        foreach ($skins as $k => $v) {
            echo '<option value="' . $skins[$k]['name'] . '" ';
    
            if ($skins[$k]['name'] == $skin) {
                echo 'selected="selected"';
            } elseif ($skin == '' and $skins[$k]['name'] == 'default') {
                echo 'selected="selected"';
            }
    
            echo '>' . $skins[$k]['name'] . '</option>';
        }
    
        echo '
                    </select>
                    <label for="skins">' . translate("Choisir une charte graphique") . '</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="skin_thumbnail"></div>
                </div>
            </div>
            <input type="hidden" name="uname" value="' . $userinfo['uname'] . '" />
            <input type="hidden" name="uid" value="' . $userinfo['uid'] . '" />
            <input type="hidden" name="op" value="savetheme" />
            <input class="btn btn-primary my-3" type="submit" value="' . translate("Sauver les modifications") . '" />
        </form>
        <script type="text/javascript">
        //<![CDATA[
        $(function () {
            $("#theme_local").change(function () {
                sk = $("#theme_local option:selected").text().substr(-3);
                if(sk=="_sk") {
                    $("#skin_choice").removeClass("collapse");
                    $("#skins").change(function () {
                    sl = $("#skins option:selected").text();
                    $("#skin_thumbnail").html(\'<a href="assets/skins/\'+sl+\'" class="btn btn-outline-primary"><img class="img-fluid img-thumbnail" src="assets/skins/\'+sl+\'/thumbnail.png" /></a>\');
                    }).change();
                } else {
                    $("#skin_choice").addClass("collapse");
                    $("#skin_thumbnail").html(\'\');
                }
            })
            .change();
        });
        //]]
        </script>';
    }
    
    /**
     * [savetheme description]
     *
     * @param   [type]  $uid    [$uid description]
     * @param   [type]  $theme  [$theme description]
     *
     * @return  [type]          [return description]
     */
    public function savetheme($uid, $theme)
    {
        global $user;
    
        $cookie = Cookie::cookiedecode($user);
    
        $result = sql_query("SELECT uid FROM users WHERE uname='$cookie[1]'");
        list($vuid) = sql_fetch_row($result);
    
        if ($uid == $vuid) {
            sql_query("UPDATE users SET theme='$theme' WHERE uid='$uid'");
    
            $userinfo = getusrinfo($user);
    
            Cookie::docookie($userinfo['uid'], $userinfo['uname'], $userinfo['pass'], $userinfo['storynum'], $userinfo['umode'], $userinfo['uorder'], $userinfo['thold'], $userinfo['noscore'], $userinfo['ublockon'], $theme, $userinfo['commentmax'], '');
            
            // Include cache manager for purge cache Page
            $cache_obj = new SuperCacheManager();
            $cache_obj->UsercacheCleanup();
    
            Header("Location: user.php");
        } else {
            Header("Location: index.php");
        }
    }

}