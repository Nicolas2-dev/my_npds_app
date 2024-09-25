<?php

namespace App\Modules\Npds\Controllers\Admin;

use App\Modules\Npds\Core\AdminController;


class AdminReferer extends AdminController
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
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    // public function __construct()
    // {
        // $f_meta_nom = 'hreferer';
        // $f_titre = __d('npds', 'Sites Référents');
        
        // //==> controle droit
        // admindroits($aid, $f_meta_nom);
        // //<== controle droit
        
        // $hlpfile = "language/manuels/Config::get('npds.language')/referer.html";

        // settype($filter, 'integer');

        // switch ($op) {
        //     case 'hreferer':
        //         hreferer($filter);
        //         break;
        
        //     case 'archreferer':
        //         archreferer($filter);
        //         break;
        
        //     case 'delreferer':
        //         delreferer();
        //         break;
        // }
    // }

    function hreferer($filter)
    {
        settype($filter, 'integer');
    
        if (!$filter) 
            $filter = 2048;
    
        echo '
        <hr />
        <h3>' . __d('npds', 'Qui parle de nous ?') . '</h3>
        <form action="admin.php" method="post">
            <input type="hidden" name="op" value="hreferer" />
            <div class="mb-3 row">
                <label class="col-form-label col-sm-4" for="filter">' . __d('npds', 'Filtre') . '</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" name="filter" min="0" max="99999" value="' . $filter . '" />
                </div>
                <div class="col-sm-4 xs-hidden"></div>
                <div class="clearfix"></div>
            </div>
        </form>
        <table id ="tad_refe" data-toggle="table" data-striped="true" data-search="true" data-show-toggle="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa" data-buttons-class="outline-secondary">
        <thead>
            <tr>
                <th data-sortable="true" data-halign="center">Url</th>
                <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">Hit</th>
            </tr>
        </thead>
        <tbody>';
    
        $hresult = sql_query("SELECT url, COUNT(url) AS TheCount, substring(url,1,$filter) AS filter FROM referer GROUP BY filter ORDER BY TheCount DESC");
        
        while (list($url, $TheCount) = sql_fetch_row($hresult)) {
            echo '
            <tr>
                <td>';
    
            if ($TheCount == 1) 
                echo '<a href="' . $url . '" target="_blank">';
    
            if ($filter != 2048)
                echo '<span>' . substr($url, 0, $filter) . '</span><span class="text-muted">' . substr($url, $filter) . '</span>';
            else
                echo $url;
    
            if ($TheCount == 1) 
                echo '</a>';
    
            echo '</a></td>
                <td>' . $TheCount . '</td>
            </tr>';
        }
    
        echo '
        </tbody>
        </table>
        <br />
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="text-danger nav-link" href="admin.php?op=delreferer" >' . __d('npds', 'Effacer les Référants') . '</a></li>
            <li class="nav-item"><a class="nav-link" href="admin.php?op=archreferer&amp;filter=' . $filter . '">' . __d('npds', 'Archiver les Référants') . '</a></li>
        </ul>';
        
       adminfoot('', '', '', '');
    }
    
    function delreferer()
    {
        sql_query("DELETE FROM referer");
    
        Header("Location: admin.php?op=AdminMain");
    }
    
    function archreferer($filter)
    {
        $file = fopen("slogs/referers.log", "w");
        $content = "===================================================\n";
        $content .= "Date : " . date("d-m-Y") . "-/- App - HTTP Referers\n";
        $content .= "===================================================\n";
        $result = sql_query("SELECT url FROM referer");
    
        while (list($url) = sql_fetch_row($result)) {
            $content .= "$url\n";
        }
    
        $content .= "===================================================\n";
        fwrite($file, $content);
        fclose($file);
    
        Header("Location: admin.php?op=hreferer&filter=$filter");
    }

}
