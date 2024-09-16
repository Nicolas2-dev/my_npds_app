<?php

use Npds\Support\Facades\DB;
use App\Modules\Theme\Support\Facades\Theme;
use App\Modules\Groupes\Support\Facades\Groupe;


/**
 * Bloc des groupes
 * 
 * syntaxe : function#bloc_groupes
 * 
 * params#Aff_img_groupe(0 ou 1)
 * 
 * Si le bloc n'a pas de titre, 'Les groupes' sera utilisé. 
 * Liste des groupes AVEC membres et lien pour demande d'adhésion pour l'utilisateur.
 * 
 * @param   [type]  $im  [$im description]
 *
 * @return  [type]       [return description]
 */
function bloc_groupes($im)
{
    global $block_title, $user;

    Theme::themesidebox(($block_title == '' ? __d('groupes', 'Les groupes') : $block_title), Groupe::fab_groupes_bloc($user, $im));
}

/**
 * Bloc du WorkSpace
 * 
 * syntaxe : function#bloc_espace_groupe
 * 
 * params#ID_du_groupe, Aff_img_groupe(0 ou 1)
 * 
 * Si le bloc n'a pas de titre, Le nom du groupe sera utilisé
 *
 * @param   [type]  $gr    [$gr description]
 * @param   [type]  $i_gr  [$i_gr description]
 *
 * @return  [type]         [return description]
 */
function bloc_espace_groupe($gr, $i_gr)
{
    global $block_title;

    if ($block_title == '') {
        $rsql = DB::table('groupes')->select('groupe_name')->where('groupe_id', $gr)->first();

        $title = $rsql['groupe_name'];
    } else {
        $title = $block_title;
    }

    Theme::themesidebox($title, Groupe::fab_espace_groupe($gr, 0, $i_gr));
}