<?php

namespace Modules\Sections\Controllers\Admin\Order;

use Modules\Npds\Core\AdminController;


class OrderUpdate extends AdminController
{
/**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = "";

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = '';


    /**
     * Call the parent construct
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
        $this->f_titre = __d('', '');

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
     * @param [type] $rubid
     * @param [type] $artid
     * @param [type] $secid
     * @param [type] $op
     * @param [type] $ordre
     * @return void
     */
    public function updateordre($rubid, $artid, $secid, $op, $ordre)
    {
        if ($radminsuper != 1) {
            Header("Location: admin.php?op=sections");
        }
    
        if ($op == "majmodule") {
            $i = count($rubid);
    
            for ($j = 1; $j < ($i + 1); $j++) {
                $rub = $rubid[$j];
                $ord = $ordre[$j];
    
                $result = sql_query("UPDATE rubriques SET ordre='$ord' WHERE rubid='$rub'");
            }
        }
    
        if ($op == "majchapitre") {
            $i = count($secid);
    
            for ($j = 1; $j < ($i + 1); $j++) {
                $sec = $secid[$j];
                $ord = $ordre[$j];
    
                $result = sql_query("UPDATE sections SET ordre='$ord' WHERE secid='$sec'");
            }
        }
    
        if ($op == "majcours") {
            $i = count($artid);
    
            for ($j = 1; $j < ($i + 1); $j++) {
                $art = $artid[$j];
                $ord = $ordre[$j];
    
                $result = sql_query("UPDATE seccont SET ordre='$ord' WHERE artid='$art'");
            }
        }
    
        Header("Location: admin.php?op=sections");
    }

}
