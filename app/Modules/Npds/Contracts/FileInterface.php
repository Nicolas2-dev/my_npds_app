<?php

namespace App\Modules\Npds\Contracts;


interface FileInterface {

    /**
     * [Size description]
     *
     * @return  [type]  [return description]
     */
    public function Size();

    /**
     * [Extention description]
     *
     * @return  [type]  [return description]
     */
    public function Extention();
    
    /**
     * [Affiche_Size description]
     *
     * @param   [type]    $Format  [$Format description]
     * @param   CONVERTI           [ description]
     *
     * @return  [type]             [return description]
     */
    public function Affiche_Size($Format = "CONVERTI");    

    /**
     * [Affiche_Extention description]
     *
     * @param   [type]  $Format  [$Format description]
     *
     * @return  [type]           [return description]
     */
    public function Affiche_Extention($Format);
    
}
