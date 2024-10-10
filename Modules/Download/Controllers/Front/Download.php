<?php

namespace Modules\Download\Controllers\Front;

use Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class DownloadAdmin extends FrontController
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
    public function main()
    {
        global $dcategory, $sortby, $sortorder;
    
        $dcategory  = removeHack(stripslashes(htmlspecialchars(urldecode($dcategory ?: ''), ENT_QUOTES, cur_charset))); // electrobug
        $dcategory = str_replace("&#039;", "\'", $dcategory);
        $sortby  = removeHack(stripslashes(htmlspecialchars(urldecode($sortby ?: ''), ENT_QUOTES, cur_charset))); // electrobug
    
        echo '
        <h2>' . __d('download', 'Chargement de fichiers') . '</h2>
        <hr />';
    
        tlist();
    
        if ($dcategory != __d('download', 'Aucune catégorie'))
            listdownloads($dcategory, $sortby, $sortorder);
    
        if (file_exists("static/download_ban.txt"))
            include("static/download_ban.txt");
    }

}
