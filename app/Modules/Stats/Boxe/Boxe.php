<?php

use Npds\view\View;
use Npds\Config\Config;
use App\Modules\Npds\Support\Sanitize;
use App\Modules\Stats\Support\Facades\Stat;
use App\Modules\Theme\Support\Facades\Theme;


/**
 * Bloc activité du site
 *
 * syntaxe : function#Site_Activ
 * 
 * @return  [type]  [return description]
 */
function Site_Activ()
{
    list($membres, $totala, $totalb, $totalc, $totald, $totalz) = Stat::req_stat();

    $data = [
        'startdate'     => Config::get('npds.startdate'),
        'top'           => Config::get('npds.top'),
        'totalz'        => Sanitize::wrh($totalz),
        'membres'       => Sanitize::wrh($membres),
        'totala'        => Sanitize::wrh($totala),
        'totalc'        => Sanitize::wrh($totalc) ,
        'totald'        => Sanitize::wrh($totald),
        'totalb'        => Sanitize::wrh($totalb),
        'imgtmptop'     => (Theme::theme_image('box/top.gif') ?: false),
        'imgtmpstat'    => (Theme::theme_image('box/stat.gif') ?: false),
    ];

    global $block_title;

    Theme::themesidebox($block_title ?: __d('stats', 'Activité du site'), View::make('Modules/Stats/Views/Boxe/Site_Activ', $data));
}