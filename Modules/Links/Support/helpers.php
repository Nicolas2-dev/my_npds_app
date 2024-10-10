<?php

use Npds\Config\Config;

/**
 * Undocumented function
 *
 * @return void
 */
function ban_01()
{
    // Le système de bannière
    if ((Config::get('npds.banners')) and function_exists("viewbanner")) {
        echo '<p class="text-center">';
        viewbanner();
        echo '</p>';
    }
}

/**
 * Undocumented function
 *
 * @return void
 */
function ban_02()
{
    echo '<p class="text-center"><strong> [ Votre publicit&eacute; Ici - Contactez le webmestre du site ]</strong></p>';
}

/**
 * Undocumented function
 *
 * @return void
 */
function ban_03()
{
    echo '<p class="text-center"><strong> [ Annonceur -::- Votre publicit&eacute; <a href=mailto:>ICI</a> ]</strong></p>';
}
