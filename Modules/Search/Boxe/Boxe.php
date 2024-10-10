<?php

use Npds\view\View;
use Modules\Theme\Support\Facades\Theme;


/**
 * Bloc Search-engine
 * 
 * syntaxe : function#searchbox
 *
 * @return  [type]  [return description]
 */
function searchbox()
{
    global $block_title;

    $title = $block_title == '' ? __d('search', 'Recherche') : $block_title;

    Theme::themesidebox($title, View::make('Modules/Search/Views/Boxe/SearchBoxe'));
}
