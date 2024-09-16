<?php

use App\Modules\Groupes\Library\GroupeManager;


if (! function_exists('valid_group'))
{
    /**
     * [valid_group description]
     *
     * @param   [type]  $xuser  [$xuser description]
     *
     * @return  [type]          [return description]
     */
    function valid_group($xuser)
    {
        return GroupeManager::getInstance()->valid_group($xuser);
    }
}

if (! function_exists('liste_groue'))
{
    /**
     * [liste_group description]
     *
     * @return  [type]  [return description]
     */
    function liste_group()
    {
        return GroupeManager::getInstance()->liste_group();
    }
}

if (! function_exists('groupe_forum'))
{
    /**
     * [groupe_forum description]
     *
     * @param   [type]  $forum_groupeX  [$forum_groupeX description]
     * @param   [type]  $tab_groupeX    [$tab_groupeX description]
     *
     * @return  [type]                  [return description]
     */
    function groupe_forum($forum_groupeX, $tab_groupeX)
    {
        return GroupeManager::getInstance()->groupe_forum($forum_groupeX, $tab_groupeX);
    }
}

if (! function_exists('groupe_autorisation'))
{
    /**
     * [groupe_autorisation description]
     *
     * @param   [type]  $groupeX      [$groupeX description]
     * @param   [type]  $tab_groupeX  [$tab_groupeX description]
     *
     * @return  [type]                [return description]
     */
    function groupe_autorisation($groupeX, $tab_groupeX)
    {
        return GroupeManager::getInstance()->groupe_autorisation($groupeX, $tab_groupeX);
    }
}

if (! function_exists('fab_espace_groupe'))
{
    /**
     * [fab_espace_groupe description]
     *
     * @param   [type]  $gr    [$gr description]
     * @param   [type]  $t_gr  [$t_gr description]
     * @param   [type]  $i_gr  [$i_gr description]
     *
     * @return  [type]         [return description]
     */
    function fab_espace_groupe($gr, $t_gr, $i_gr)
    {
        return GroupeManager::getInstance()->fab_espace_groupe($gr, $t_gr, $i_gr);
    }
}

if (! function_exists('fab_groupes_bloc'))
{
    /**
     * [fab_groupes_bloc description]
     *
     * @param   [type]  $user  [$user description]
     * @param   [type]  $im    [$im description]
     *
     * @return  [type]         [return description]
     */
    function fab_groupes_bloc($user, $im)
    {
        return GroupeManager::getInstance()->fab_groupes_bloc($user, $im);
    }
}
