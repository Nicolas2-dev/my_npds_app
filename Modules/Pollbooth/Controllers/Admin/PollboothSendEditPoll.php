<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class PollboothSendEditPoll extends AdminController
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
     * case 'SendEditPoll': => poll_SendEditPoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_SendEditPoll()
    {
        global $pollTitle, $optionText, $poll_type, $pollID, $poll_close;
    
        $result = sql_query("UPDATE poll_desc SET pollTitle='$pollTitle' WHERE pollID='$pollID'");
    
        $poll_type = $poll_type + 128 * $poll_close;
    
        for ($i = 1; $i <= sizeof($optionText); $i++) {
            if ($optionText[$i] != '') {
                $optionText[$i] = Sanitize::FixQuotes($optionText[$i]);
            }
    
            $result = sql_query("UPDATE poll_data SET optionText='$optionText[$i]', pollType='$poll_type' WHERE pollID='$pollID' and voteID='$i'");
        }
    
        Header("Location: admin.php?op=create");
    }

}
