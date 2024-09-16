<?php

namespace App\Modules\Npds\Contracts;


interface SubscribeInterface {

    /**
     * [subscribe_mail description]
     *
     * @param   [type]  $Xtype    [$Xtype description]
     * @param   [type]  $Xtopic   [$Xtopic description]
     * @param   [type]  $Xforum   [$Xforum description]
     * @param   [type]  $Xresume  [$Xresume description]
     * @param   [type]  $Xsauf    [$Xsauf description]
     *
     * @return  [type]            [return description]
     */
    public function subscribe_mail($Xtype, $Xtopic, $Xforum, $Xresume, $Xsauf);

    /**
     * [subscribe_query description]
     *
     * @param   [type]  $Xuser  [$Xuser description]
     * @param   [type]  $Xtype  [$Xtype description]
     * @param   [type]  $Xclef  [$Xclef description]
     *
     * @return  [type]          [return description]
     */
    function subscribe_query($Xuser, $Xtype, $Xclef);

}
