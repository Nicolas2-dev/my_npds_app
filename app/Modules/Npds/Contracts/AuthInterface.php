<?php

namespace App\Modules\Npds\Contracts;


interface AuthInterface {

    /**
     * [secur_static description]
     *
     * @param   [type]  $sec_type  [$sec_type description]
     *
     * @return  [type]             [return description]
     */
    public function secur_static($sec_type);

    /**
     * [autorisation description]
     *
     * @param   [type]  $auto  [$auto description]
     *
     * @return  [type]         [return description]
     */
    public function autorisation($auto);
    
    /**
     * [is_admin description]
     *
     * @param   [type]  $xadmin  [$xadmin description]
     *
     * @return  [type]           [return description]
     */
    public function is_admin($xadmin);
    
    /**
     * [is_user description]
     *
     * @param   [type]  $xuser  [$xuser description]
     *
     * @return  [type]          [return description]
     */
    public function is_user($xuser);    

}
