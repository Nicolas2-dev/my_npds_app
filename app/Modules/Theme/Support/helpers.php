<?php


use App\Modules\Theme\Library\ThemeManager;
use App\Modules\Users\Support\Facades\User;

if (! function_exists('theme_image'))
{
    /**
     * [theme_image description]
     *
     * @param   [type]  $theme_img  [$theme_img description]
     *
     * @return  [type]              [return description]
     */
    function theme_image($theme_img)
    {
        return ThemeManager::getInstance()->theme_image($theme_img);
    }
}

if (! function_exists('themepreview'))
{
    
    function themepreview($title, $hometext, $bodytext = '', $notes = '')
    {
        return ThemeManager::getInstance()->themepreview($title, $hometext, $bodytext, $notes);
    }
}

if (! function_exists('member_menu'))
{
    /**
     * [member_menu description]
     *
     * @param   [type]  $mns  [$mns description]
     * @param   [type]  $qui  [$qui description]
     *
     * @return  [type]        [return description]
     */
    function member_menu($mns, $qui)
    {
        return User::member_menu($mns, $qui);
    }
}

