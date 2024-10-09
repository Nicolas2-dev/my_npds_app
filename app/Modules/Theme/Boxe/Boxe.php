<?php

use Npds\view\View;
use Npds\Config\Config;
use App\Modules\Theme\Support\Facades\Theme;


/**
 * une liste de sélection des skins disponible pour le thème courant
 * visualisation de la page courante avec le skin choisi
 * 
 * function#boxe_skin
 * 
 * @return  [type]  [return description]
 */
function boxe_skin()
{
    global $user;

    global $block_title;
    $title = $block_title == '' ? __d('theme', 'Choisir un skin') : $block_title;

    if ($user) {
        $user2  = base64_decode($user);
        $cookie = explode(':', $user2);
        $ibix   = explode('+', urldecode($cookie[9]));

        $skinOn = substr($ibix[0], -3) != '_sk' ? '' : $ibix[1];
    } else {
        $skinOn = substr(Config::get('npds.Default_Theme'), -3) != '_sk' ? '' : Config::get('npds.Default_Skin');
    }

    Theme::themesidebox($title, View::make('Modules/Theme/Views/Boxe/Boxe_Skin', ['skinOn' => $skinOn]));
}
