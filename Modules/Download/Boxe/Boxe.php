<?php

use Npds\view\View;
use Modules\Theme\Support\Facades\Theme;
use Modules\download\Support\Facades\Download;


/**
 * Bloc topdownload
 * 
 * syntaxe : function#topdownload
 *
 * @return  [type]  [return description]
 */
function topdownload()
{
    global $block_title;

    $download_data = Download::topdownload_data('short', 'dcounter');

    Theme::themesidebox(
        ($block_title ?: __d('download', 'Les plus téléchargés')), 
        View::make('Modules/Download/Views/Boxe/top_download', compact('download_data'))
    );
}

/**
 * Bloc lastdownload
 * 
 * syntaxe : function#lastdownload
 *
 * @return  [type]  [return description]
 */
function lastdownload()
{
    global $block_title;

    $download_data = Download::topdownload_data('short', 'ddate');

    Theme::themesidebox(
        ($block_title ?: __d('download', 'Fichiers les plus récents')), 
        View::make('Modules/Download/Views/Boxe/last_download', compact('download_data'))
    );

}
