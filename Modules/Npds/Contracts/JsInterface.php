<?php

namespace Modules\Npds\Contracts;


interface JsInterface {

    /**
     * [auto_complete description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $temps_cache   [$temps_cache description]
     *
     * @return  [type]                 [return description]
     */
    public function auto_complete($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $temps_cache);

    /**
     * [auto_complete_multi description]
     *
     * @param   [type]  $nom_array_js  [$nom_array_js description]
     * @param   [type]  $nom_champ     [$nom_champ description]
     * @param   [type]  $nom_tabl      [$nom_tabl description]
     * @param   [type]  $id_inpu       [$id_inpu description]
     * @param   [type]  $req           [$req description]
     *
     * @return  [type]                 [return description]
     */
    public function auto_complete_multi($nom_array_js, $nom_champ, $nom_tabl, $id_inpu, $req);

}