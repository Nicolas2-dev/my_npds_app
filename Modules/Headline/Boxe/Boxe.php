<?php

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use Modules\Theme\Support\Facades\Theme;
use Modules\Headline\Support\Facades\Headline;


/**
 * Bloc HeadLines
 * 
 * function#headlines
 * params#ID_du_canal
 *
 * @param   [type]$hid    [$hid description]
 * @param   [type]$block  [$block description]
 * @param   true          [ description]
 *
 * @return  [type]        [return description]
 */
function headlines($hid = '', $block = true)
{
    $query = DB::table('headlines')->select('sitename', 'url', 'headlinesurl', 'hid');

    if (!empty($hid)) {
        $query->where('hid', $hid);
    }

    foreach ($query->where('status', 1)->get() as $headline) {
        $cache_file = Headline::cache_file_headline($headline['sitename'], $hid);

        if ((!(file_exists($cache_file))) 
            or (filemtime($cache_file) < (time() - Config::get('headline.config.cache_time'))) 
            or (!(filesize($cache_file)))) 
        {
            if (!Headline::verifie_rss_host($headline['url'])) {
                // 
                Headline::cache_file_security($cache_file, $headline['sitename']);

            } else {
                //
                Headline::generate_cache_flux($cache_file, $headline['headlinesurl']);
            }
        }
        
        $boxstuff = Headline::read_cache_flux($cache_file, $headline['url']);

        if ($block) {
            Theme::themesidebox($headline['sitename'], $boxstuff);
        } else {
            return $boxstuff;
        }
    }
}
