<?php

use Npds\Support\Facades\DB;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;


/**
 * Bloc principal
 * 
 * syntaxe : function#mainblock
 *
 * @return  [type]  [return description]
 */
function mainblock()
{
    global $block_title;    

    $block = DB::table('block')->select('title', 'content')->where('id', 1)->first();

    Theme::themesidebox(
        Language::aff_langue($block['title'] ?: $block_title), 
        Language::aff_langue(preg_replace_callback('#<a href=[^>]*(&)[^>]*>#', [Sanitize::class, 'changetoamp'], $block['content']))
    );
}
