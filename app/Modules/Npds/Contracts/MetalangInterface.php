<?php

namespace App\Modules\Npds\Contracts;


interface MetalangInterface {

    /**
     * [arg_filter description]
     *
     * @param   [type]  $arg  [$arg description]
     *
     * @return  [type]        [return description]
     */
    public function arg_filter($arg);
 
    /**
     * [charg description]
     *
     * @param   [type]  $funct      [$funct description]
     * @param   [type]  $arguments  [$arguments description]
     *
     * @return  [type]              [return description]
     */
    public function charg($funct, $arguments);
    
    /**
     * [match_uri description]
     *
     * @param   [type]  $racine  [$racine description]
     * @param   [type]  $R_uri   [$R_uri description]
     *
     * @return  [type]           [return description]
     */
    public function match_uri($racine, $R_uri);
    
    /**
     * [charg_metalang description]
     *
     * @return  [type]  [return description]
     */
    public function charg_metalang();    

    /**
     * [ana_args description]
     *
     * @param   [type]  $arg  [$arg description]
     *
     * @return  [type]        [return description]
     */
    public function ana_args($arg);

    /**
     * [meta_lang description]
     *
     * @param   [type]  $Xcontent  [$Xcontent description]
     *
     * @return  [type]             [return description]
     */
    public function meta_lang($Xcontent);    

}
