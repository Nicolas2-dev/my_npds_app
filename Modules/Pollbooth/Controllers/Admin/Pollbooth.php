<?php

namespace Modules\Pollbooth\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Core\AdminController;


class Pollbooth extends AdminController
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
     * case 'create': => poll_createPoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_createPoll()
    {
        global $id;
    
        echo '
            <hr />
                <h3 class="mb-3">' . __d('pollbooth', 'Liste des sondages') . '</h3>
                <table id="tad_pool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                    <th class="n-t-col-xs-1" data-sortable="true" data-halign="center" data-align="right">ID</th>
                    <th data-sortable="true" data-halign="center">' . __d('pollbooth', 'Intitulé du Sondage') . '</th>
                    <th class="n-t-col-xs-2" data-sortable="true" data-halign="center" data-align="right">' . __d('pollbooth', 'Vote') . '</th>
                    <th class="n-t-col-xs-2" data-halign="center" data-align="center">' . __d('pollbooth', 'Fonctions') . '</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT pollID, pollTitle, voters FROM poll_desc ORDER BY timeStamp");
        
        while ($object = sql_fetch_assoc($result)) {
            echo '
                    <tr>
                    <td>' . $object["pollID"] . '</td>
                    <td>' . aff_langue($object["pollTitle"]) . '</td>
                    <td>' . $object["voters"] . '</td>
                    <td>
                        <a href="admin.php?op=editpollPosted&amp;id=' . $object["pollID"] . '"><i class="fa fa-edit fa-lg" title="' . __d('pollbooth', 'Editer ce sondage') . '" data-bs-toggle="tooltip"></i></a>
                        <a href="admin.php?op=removePosted&amp;id=' . $object["pollID"] . '"><i class="fas fa-trash fa-lg text-danger ms-2" title="' . __d('pollbooth', 'Effacer ce sondage') . '" data-bs-toggle="tooltip"></i></a>
                    </td>
                    </tr>';
    
            $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM poll_data WHERE pollID='" . $object["pollID"] . "'");
            list($sum) = sql_fetch_row($result2);
        }
    
        echo '
                </tbody>
            </table>
            <hr />
            <h3 class="mb-3">' . __d('pollbooth', 'Créer un nouveau Sondage') . '</h3>
            <form id="pollssondagenew" action="admin.php" method="post">
                <input type="hidden" name="op" value="createPosted" />
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="pollTitle" name="pollTitle" id="pollTitle" maxlength="100" required="required" />
                    <label for="pollTitle">' . __d('pollbooth', 'Intitulé du Sondage') . '</label>
                    <span class="help-block">' . __d('pollbooth', 'S.V.P. entrez chaque option disponible dans un seul champ') . '</span>
                    <span class="help-block text-end"><span id="countcar_pollTitle"></span></span>
                </div>';
    
        $requi = '';
    
        for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
            $requi = $i < 3 ? ' required="required" ' : '';
    
            echo '
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" id="optionText' . $i . '" name="optionText[' . $i . ']" maxlength="255" ' . $requi . ' />
                    <label for="optionText' . $i . '">' . __d('pollbooth', 'Option') . ' ' . $i . '</label>
                    <span class="help-block text-end"><span id="countcar_optionText' . $i . '"></span></span>
                </div>';
        }
    
        echo '
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" id="poll_type" name="poll_type" value="1" />
                    <label class="form-check-label" for="poll_type">' . __d('pollbooth', 'Seulement aux membres') . '</label>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">' . __d('pollbooth', 'Créer') . '</button>
                </div>
            </form>';
    
        $arg1 = '
        var formulid = ["pollssondagenew"];
        inpandfieldlen("pollTitle",100)';
    
        for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
            $arg1 .= '
            inpandfieldlen("optionText' . $i . '",255)';
        }
    
        adminfoot('fv', '', $arg1, '');
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
    
        $pollTitle = FixQuotes($pollTitle);
    
        $result = sql_query("INSERT INTO poll_desc VALUES (NULL, '$pollTitle', '$timeStamp', 0)");
        $object = sql_fetch_assoc(sql_query("SELECT pollID FROM poll_desc WHERE pollTitle='$pollTitle'"));
    
        $id = $object['pollID'];
    
        for ($i = 1; $i <= sizeof($optionText); $i++) {
            if ($optionText[$i] != '') {
                $optionText[$i] = FixQuotes($optionText[$i]);
            }
    
            $result = sql_query("INSERT INTO poll_data (pollID, optionText, optionCount, voteID, pollType) VALUES ('$id', '$optionText[$i]', 0, '$i', '$poll_type')");
        }
    
        Header("Location: admin.php?op=adminMain");
    }
    
    /**
     * case 'remove': => poll_removePoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_removePoll()
    {
        echo '
        <hr />
        <h3 class="mb-3">' . __d('pollbooth', 'Retirer un Sondage existant') . '</h3>
        <span class="help-block">' . __d('pollbooth', 'S.V.P. Choisissez un sondage dans la liste suivante.') . '</span>
        <p align="center"><span class="text-danger">' . __d('pollbooth', 'ATTENTION : Le Sondage choisi va être supprimé IMMEDIATEMENT de la base de données !') . '</span></p>
        ';
    
        echo '
        <form action="admin.php" method="post">
            <input type="hidden" name="op" value="removePosted" />
            <table id="tad_delepool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                    <th></th>
                    <th data-sortable="true">' . __d('pollbooth', 'Intitulé du Sondage') . '</th>
                    <th data-sortable="true">ID</th>
                    </tr>
                </thead>
                <tbody>';
    
        $result = sql_query("SELECT pollID, pollTitle FROM poll_desc ORDER BY timeStamp");
    
        while ($object = sql_fetch_assoc($result)) {
            echo '
                    <tr>
                    <td><input type="radio" name="id" value="' . $object['pollID'] . '" /></td>
                    <td> ' . $object['pollTitle'] . '</td>
                    <td>ID : ' . $object['pollID'] . '</td>
                    </tr>
            ';
        }
    
        echo '
                </tbody>
            </table>
            <br />
            <div class="mb-3">
                <button class="btn btn-danger" type="submit">' . __d('pollbooth', 'Retirer') . '</button>
            </div>
        </form>';
    
        // include('footer.php');
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
    
    /**
     * case 'editpoll': => poll_editPoll();
     * 
     * Undocumented function
     *
     * @return void
     */
    public function poll_editPoll()
    {
        $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM poll_desc ORDER BY timeStamp");

        echo '
        <hr />
        <h3 class="mb-3">' . __d('pollbooth', 'Edition des sondages') . '</h3>
        <span class="help-block">' . __d('pollbooth', 'S.V.P. Choisissez un sondage dans la liste suivante.') . '</span>
        <form id="fad_editpool" action="admin.php" method="post">
            <input type="hidden" name="op" value="editpollPosted" />
            <table id="tad_editpool" data-toggle="table" data-striped="true" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
                <thead>
                    <tr>
                    <th></th>
                    <th data-sortable="true">' . __d('pollbooth', 'Intitulé du Sondage') . '</th>
                    <th data-sortable="true">ID</th>
                    </tr>
                </thead>
                <tbody>';
    
        while ($object = sql_fetch_assoc($result)) {
            echo '
                    <tr>
                    <td><input type="radio" name="id" value="' . $object['pollID'] . '" /></td>
                    <td>' . $object['pollTitle'] . '</td>
                    <td>ID : ' . $object['pollID'] . '</td>
                    </tr>';
        }
    
        echo '
                </tbody>
            </table>
            <br />
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">' . __d('pollbooth', 'Editer') . '</button>
            </div>
        </form>';
    
        //   adminfoot('','','','');
        // include('footer.php');
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
    
            adminfoot('fv', '', $arg1, '');
        } else {
            header("location: admin.php?op=editpoll");
        }
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
                $optionText[$i] = FixQuotes($optionText[$i]);
            }
    
            $result = sql_query("UPDATE poll_data SET optionText='$optionText[$i]', pollType='$poll_type' WHERE pollID='$pollID' and voteID='$i'");
        }
    
        Header("Location: admin.php?op=create");
    }

}
