<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class PollboothCreatePosted extends AdminController
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
     * case 'createPosted': => poll_createPosted();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_createPosted()
    {
        global $pollTitle, $optionText, $poll_type;
    
        $timeStamp = time();
    
        $pollTitle = Sanitize::FixQuotes($pollTitle);
    
        $result = sql_query("INSERT INTO poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0)");
        $object = sql_fetch_assoc(sql_query("SELECT pollID FROM poll_desc WHERE pollTitle='$pollTitle'"));
    
        $id = $object['pollID'];
    
        for ($i = 1; $i <= sizeof($optionText); $i++) {
            if ($optionText[$i] != '') {
                $optionText[$i] = Sanitize::FixQuotes($optionText[$i]);
            }
    
            $result = sql_query("INSERT INTO poll_data (pollID, optionText, optionCount, voteID, pollType) VALUES ('$id', '$optionText[$i]', 0, '$i', '$poll_type')");
        }
    
        Header("Location: admin.php?op=adminMain");
    }

}
