<?php

namespace App\Modules\Npds\Contracts;


interface LanguageInterface {

    /**
     * [aff_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_langue($ibid);

    /**
     * [make_tab_langue description]
     *
     * @return  [type]  [return description]
     */
    public function make_tab_langue();

    /**
     * [aff_localzone_langue description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_localzone_langue($ibid);

    /**
     * [aff_local_langue description]
     *
     * @param   [type]  $ibid_index  [$ibid_index description]
     * @param   [type]  $ibid        [$ibid description]
     * @param   [type]  $mess        [$mess description]
     *
     * @return  [type]               [return description]
     */
    public function aff_local_langue($ibid_index, $ibid, $mess = '');
    
    /**
     * [preview_local_langue description]
     *
     * @param   [type]  $local_user_language  [$local_user_language description]
     * @param   [type]  $ibid                 [$ibid description]
     *
     * @return  [type]                        [return description]
     */
    public function preview_local_langue($local_user_language, $ibid);
    
    /**
     * [language_iso description]
     *
     * @param   [type]  $l  [$l description]
     * @param   [type]  $s  [$s description]
     * @param   [type]  $c  [$c description]
     *
     * @return  [type]      [return description]
     */
    public function language_iso($l, $s, $c);    

}
