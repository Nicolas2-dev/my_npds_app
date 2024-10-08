<?php

namespace App\Modules\Theme\Contracts;


interface ThemeInterface {

    /**
     * [theme_image description]
     *
     * @param   [type]  $theme_img  [$theme_img description]
     *
     * @return  [type]              [return description]
     */
    public function theme_image($theme_img);

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
    public function themepreview($title, $hometext, $bodytext = '', $notes = '');

    /**
     * [theme_path description]
     *
     * @return  [type]  [return description]
     */
    public function theme_path();

}
