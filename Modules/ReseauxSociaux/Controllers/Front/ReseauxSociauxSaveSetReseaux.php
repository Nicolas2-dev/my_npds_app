<?php

namespace Modules\ReseauxSociaux\Controllers\Front;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Hack;


class ReseauxSociauxSaveSetReseaux extends FrontController
{

    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;


    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function SaveSetReseaux()
    {
        global $cookie;

        $li_rs = '';

        foreach ($_POST['rs'] as $v1) {
            if ($v1['uid'] !== '') {
                $li_rs .= $v1['id'] . '|' . $v1['uid'] . ';';
            }
        }

        $li_rs = rtrim($li_rs, ';');
        $li_rs = Hack::remove(stripslashes(Sanitize::FixQuotes($li_rs)));

        sql_query("UPDATE users_extend SET M2='$li_rs' WHERE uid='$cookie[0]'");

        Header("Location: modules.php?&ModPath=ReseauxSociaux&ModStart=$ModStart");
    }

}
