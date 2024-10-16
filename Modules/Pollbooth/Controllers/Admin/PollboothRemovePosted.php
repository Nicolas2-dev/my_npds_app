<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;


class PollboothRemovePosted extends AdminController
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
    protected $hlpfile = 'surveys';

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
    protected $f_meta_nom = 'create';


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
        $this->f_titre = __d('pollbooth', 'Les sondages');

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
     * case 'removePosted': => poll_removePosted();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_removePosted()
    {
        global $id;
    
        // ----------------------------------------------------------------------------
        // Specified the index and the name off the application for the table appli_log
        $al_id = 1;
        $al_nom = 'Poll';
    
        // ----------------------------------------------------------------------------
        if (Config::get('npds.setCookies') == '1') {
            $sql = "DELETE FROM appli_log WHERE al_id='$al_id' AND al_subid='$id'";
            sql_query($sql);
        }
    
        sql_query("DELETE FROM poll_desc WHERE pollID='$id'");
        sql_query("DELETE FROM poll_data WHERE pollID='$id'");
    
        include('modules/comments/pollBoth.conf.php');
    
        sql_query("DELETE FROM posts WHERE topic_id='$id' AND forum_id='$forum'");
    
        Header("Location: admin.php?op=create");
    }

}
