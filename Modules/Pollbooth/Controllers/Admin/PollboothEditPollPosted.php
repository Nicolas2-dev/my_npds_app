<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Css;
use Modules\Npds\Core\AdminController;


class PollboothEditPollPosted extends AdminController
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
     * case 'editpollPosted': => poll_editPollPosted();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_editPollPosted()
    {
        global $id;
    
        if ($id) {

            $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM poll_desc WHERE pollID='$id'");
            $holdtitle = sql_fetch_row($result);
    
            $result = sql_query("SELECT optionText, voteID, pollType FROM poll_data WHERE pollID='$id' ORDER BY voteID ASC");
    
            echo '
            <hr />
            <h3 class="mb-3">' . __d('pollbooth', 'Edition des sondages') . '</h3>
            <form id="pollssondageed" method="post" action="admin.php">
                <input type="hidden" name="op" value="SendEditPoll">
                <input type="hidden" name="pollID" value="' . $id . '" />
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="pollTitle" name="pollTitle" value="' . $holdtitle[1] . '" maxlength="100" required="required" />
                    <label for="pollTitle">' . __d('pollbooth', 'Intitulé du Sondage') . '</label>
                    <span class="help-block">' . __d('pollbooth', 'S.V.P. entrez chaque option disponible dans un seul champ') . '</span>
                    <span class="help-block text-end"><span id="countcar_pollTitle"></span></span>
                </div>';
    
            $requi = '';
            for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
                if ($i < 3) {
                    $requi = ' required="required" ';
                } else {
                    $requi = '';
                }
    
                list($optionText, $voteID, $pollType) = sql_fetch_row($result);
    
                echo '
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="optionText' . $i . '" name="optionText[' . $voteID . ']" maxlength="255" value="' . $optionText . '" ' . $requi . ' />
                    <label for="optionText' . $i . '">' . __d('pollbooth', 'Option') . ' ' . $i . '</label>
                    <span class="help-block text-end"><span id="countcar_optionText' . $i . '"></span></span>
                </div>';
            }
    
            $pollClose = (($pollType / 128) >= 1 ? 1 : 0);
            $pollType = $pollType % 128;
    
            echo '
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="poll_type" name="poll_type" value="1"';
    
            if ($pollType == "1") {
                echo ' checked="checked"';
            }
    
            echo ' />
                        <label class="form-check-label" for="poll_type">' . __d('pollbooth', 'Seulement aux membres') . '</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check text-danger">
                        <input class="form-check-input" type="checkbox" id="poll_close" name="poll_close" value="1"';
    
            if ($pollClose == 1) {
                echo ' checked="checked"';
            }
    
            echo ' />
                        <label class="form-check-label" for="poll_close">' . __d('pollbooth', 'Vote fermé') . '</label>
                    </div>
                </div>
                <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Ok</button>
                </div>
            </form>';
    
            $arg1 = '
            var formulid = ["pollssondageed"];
            inpandfieldlen("pollTitle",100)';
    
            for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
                $arg1 .= '
                inpandfieldlen("optionText' . $i . '",255)';
            }
    
            Css::adminfoot('fv', '', $arg1, '');
        } else {
            header("location: admin.php?op=editpoll");
        }
    }


}
