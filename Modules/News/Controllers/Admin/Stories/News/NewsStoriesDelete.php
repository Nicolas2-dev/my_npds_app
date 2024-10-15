<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class NewsStoriesDelete extends AdminController
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
    protected $hlpfile = 'newarticle';

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
    protected $f_meta_nom = 'adminStory';


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
        $this->f_titre = __d('news', 'Articles');

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
     * @param [type] $qid
     * @return void
     */
    public function deleteStory($qid)
    {
        $res = sql_query("SELECT story, bodytext FROM queue WHERE qid='$qid'");
        list($story, $bodytext) = sql_fetch_row($res);
    
        $artcomplet = $story . $bodytext;
        $rechcacheimage = '#cache/a[i|c|]\d+_\d+_\d+.[a-z]{3,4}#m';
    
        preg_match_all($rechcacheimage, $artcomplet, $cacheimages);
    
        foreach ($cacheimages[0] as $imagetodelete) {
            unlink($imagetodelete);
        }
    
        $result = sql_query("DELETE FROM queue WHERE qid='$qid'");
    
        global $aid;
        Ecr_Log("security", "deleteStoryfromQueue($qid) by AID : $aid", "");

        Header("Location: admin.php?op=submissions");
    }

}
