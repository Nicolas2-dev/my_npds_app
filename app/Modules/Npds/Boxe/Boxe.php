<?php

use Npds\Config\Config;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;



/**
 * Bloc langue
 * 
 * syntaxe : function#bloc_langue
 *
 * @return  [type]  [return description]
 */
function bloc_langue()
{
    global $block_title;

    if (Config::get('npds.multi_langue')) {
        $title = $block_title == '' ? __d('npds', 'Choisir une langue') : $block_title;

        Theme::themesidebox($title, Language::aff_local_langue(site_url('index'), 'choice_user_language', ''));
    }
}
