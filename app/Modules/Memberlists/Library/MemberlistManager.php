<?php

namespace App\Modules\Memberlists\Library;

use App\Modules\Memberlists\Contracts\MemberlistInterface;


class MemberlistManager implements MemberlistInterface 
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
     * @return void
     */
    public function alpha()
    {
        global $sortby, $list, $gr_from_ws, $uid_from_ws;

        $alphabet = array(__d('memberlists', 'Tous'), 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', __d('memberlists', 'Autres'));
        $num = count($alphabet) - 1;
        $counter = 0;

        foreach ($alphabet as $ltr) {
            echo '<a href="memberslist.php?letter=' . $ltr . '&amp;sortby=' . $sortby . '&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . $ltr . '</a>';
            
            if ($counter != $num)
                echo ' | ';

            $counter++;
        }

        echo '
        <br />
        <form action="memberslist.php" method="post">
            <div class="mb-3 row">
                <label class="col-form-label col-sm-3" for="mblst_search">' . __d('memberlists', 'Recherche') . '</label>
                <div class="col-sm-9">
                    <input class="form-control" type="input" id="mblst_search" name="letter" />
                    <input type="hidden" name="list" value="' . urldecode($list ?:  '') . '" />
                    <input type="hidden" name="gr_from_ws" value="' . $gr_from_ws . '" />
                </div>
            </div>
        </form>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function unique($ibid)
    {
        foreach ($ibid as $to_user) {
            settype($Xto_user, 'array');
            if (!array_key_exists($to_user, $Xto_user))
                $Xto_user[$to_user] = $to_user;
        }

        return ($Xto_user);
    }

    /**
     * Undocumented function
     *
     * @param [type] $letter
     * @return void
     */
    public function SortLinks($letter)
    {
        global $sortby, $list, $admin, $gr_from_ws;

        if ($letter == 'front')
            $letter = __d('memberlists', 'Tous');

        $sort = false;
        echo '<p class="">';

        echo __d('memberlists', 'Classé par ordre de : ') . " ";
        if ($sortby == "uname ASC" or !$sortby) {
            echo __d('memberlists', 'identifiant') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=uname%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'identifiant') . '</a> | ';
        
        if ($sortby == 'name ASC') {
            echo __d('memberlists', 'vrai nom') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=name%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'vrai nom') . '</a> | ';
        
        if ($sortby == 'user_avatar ASC') {
            echo __d('memberlists', 'Avatar') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=user_avatar%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'Avatar') . '</a> | ';
        
        if (($sortby == 'femail ASC') or ($sortby == 'email ASC')) {
            echo __d('memberlists', 'Email') . ' | ';
            $sort = true;
        } else {
            if ($admin) {
                echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=email%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'Email') . '</a> | ';
            } else {
                echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=femail%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'Email') . '</a> | ';
            }
        }

        if ($sortby == 'user_from ASC') {
            echo __d('memberlists', 'Localisation') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=user_from%20ASC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'Localisation') . '</a> | ';
        
        if ($sortby == 'url DESC') {
            echo __d('memberlists', 'Url') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=url%20DESC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'Url') . '</a> | ';
        
        if ($sortby == 'mns DESC') {
            echo __d('memberlists', 'MiniSite') . ' | ';
            $sort = true;
        } else
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=mns%20DESC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">' . __d('memberlists', 'MiniSite') . '</a> | ';
        
        if ($sortby == 'uid DESC') {
            echo "I.D";
            $sort = true;
        } else {
            echo '<a href="memberslist.php?letter=' . $letter . '&amp;sortby=uid%20DESC&amp;list=' . $list . '&amp;gr_from_ws=' . $gr_from_ws . '">I.D</a>';
        }
        
        if (!$sort) 
            $sortby = 'uname ASC';

        echo '</p>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $user_avatar
     * @return void
     */
    public function avatar($user_avatar)
    {
        if (!$user_avatar)
            $imgtmp = "assets/images/forum/avatar/blank.gif";
        else if (stristr($user_avatar, "users_private"))
            $imgtmp = $user_avatar;
        else {
            if ($ibid = theme_image("forum/avatar/$user_avatar")) 
                $imgtmp = $ibid;
            else 
                $imgtmp = "assets/images/forum/avatar/$user_avatar";

            if (!file_exists($imgtmp)) 
                $imgtmp = "assets/images/forum/avatar/blank.gif";
        }

        return ($imgtmp);
    }

}
