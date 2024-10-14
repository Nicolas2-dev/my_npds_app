<?php

namespace Modules\Npds\Contracts;


interface CodeInterface {

    /**
     * [change_cod description]
     *
     * @param   [type]  $r  [$r description]
     *
     * @return  [type]      [return description]
     */
    public function change_cod($r);

    /**
     * [af_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function af_cod($ibid);
    
    /**
     * [desaf_cod description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function desaf_cod($ibid);
    
    /**
     * [aff_code description]
     *
     * @param   [type]  $ibid  [$ibid description]
     *
     * @return  [type]         [return description]
     */
    public function aff_code($ibid);    

}
