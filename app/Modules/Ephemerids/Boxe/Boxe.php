<?php

use Npds\view\View;
use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Npds\Support\Facades\Language;


/**
 * Bloc ephemerid
 * 
 * syntaxe : function#ephemblock
 *
 * @return  [type]  [return description]
 */
function ephemblock()
{
    global $block_title;

    $ephem_data = [];    
    $time       = (time() + ( (int) Config::get('npds.gmt') * 3600));
    $count      = 0;    

    foreach (DB::table('ephem')
        ->select('yid', 'content')
        ->where('did', date("d", $time))
        ->where('mid', date("m", $time))
        ->orderBy('yid', 'asc')
        ->get() as $ephemerid) 
    {
        $ephem_data[] = [
            'yid'       => $ephemerid['yid'],
            'content'   => Language::aff_langue($ephemerid['content']),
            'count'     => $count,
        ];

        $count = 1;        
    }

    Theme::themesidebox(($block_title ?: __d('ephemerids', 'Ephémérides')), View::make('Modules/Ephemerids/Views/Boxe/ephem_boxe', compact('ephem_data')));
}
