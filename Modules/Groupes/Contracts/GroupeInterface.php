<?php

namespace Modules\Groupes\Contracts;


interface GroupeInterface {

    /**
     * [valid_group description]
     *
     * @param   [type]  $xuser  [$xuser description]
     *
     * @return  [type]          [return description]
     */
    public function valid_group($xuser);

    /**
     * [liste_group description]
     *
     * @return  [type]  [return description]
     */
    public function liste_group();

    /**
     * [groupe_forum description]
     *
     * @param   [type]  $forum_groupeX  [$forum_groupeX description]
     * @param   [type]  $tab_groupeX    [$tab_groupeX description]
     *
     * @return  [type]                  [return description]
     */
    public function groupe_forum($forum_groupeX, $tab_groupeX);

    /**
     * [groupe_autorisation description]
     *
     * @param   [type]  $groupeX      [$groupeX description]
     * @param   [type]  $tab_groupeX  [$tab_groupeX description]
     *
     * @return  [type]                [return description]
     */
    public function groupe_autorisation($groupeX, $tab_groupeX);

    /**
     * [fab_espace_groupe description]
     *
     * @param   [type]  $gr    [$gr description]
     * @param   [type]  $t_gr  [$t_gr description]
     * @param   [type]  $i_gr  [$i_gr description]
     *
     * @return  [type]         [return description]
     */
    public function fab_espace_groupe($gr, $t_gr, $i_gr);

    /**
     * [fab_groupes_bloc description]
     *
     * @param   [type]  $user  [$user description]
     * @param   [type]  $im    [$im description]
     *
     * @return  [type]         [return description]
     */
    public function fab_groupes_bloc($user, $im);

}