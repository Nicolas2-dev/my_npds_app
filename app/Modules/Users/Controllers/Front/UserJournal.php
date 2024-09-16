<?php

namespace App\Modules\Users\Controllers\Front;


use Npds\Routing\Url;
use Npds\Http\Request;
use Npds\Support\Facades\DB;
use App\Modules\Npds\Core\FrontController;
use App\Modules\Npds\Support\Facades\Cookie;

/**
 * [UserLogin description]
 */
class UserJournal extends FrontController
{

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();

        //     case 'editjournal':
        //         if ($user)
        //             editjournal();
        //         else
        //             Header("Location: index.php");
        //         break;
        
        //     case 'savejournal':
        //         settype($datetime, 'integer');
        
        //         savejournal($uid, $journal, $datetime);
        //         break;        
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
     * [editjournal description]
     *
     * @return  [type]  [return description]
     */
    public function editjournal()
    {
        global $user;
    
        $userinfo = getusrinfo($user);
        member_menu($userinfo['mns'], $userinfo['uname']);
    
        echo '
        <h2 class="mb-3">' . translate("Editer votre journal") . '</h2>
        <form action="user.php" method="post" name="adminForm">
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <textarea class="tin form-control" rows="25" name="journal">' . $userinfo['user_journal'] . '</textarea>'
                . aff_editeur('journal', '') . '
                </div>
            </div>
            <input type="hidden" name="uname" value="' . $userinfo['uname'] . '" />
            <input type="hidden" name="uid" value="' . $userinfo['uid'] . '" />
            <input type="hidden" name="op" value="savejournal" />
            <div class="mb-3 row">
                <div class="col-12">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="datetime" name="datetime" value="1" />
                    <label class="form-check-label" for="datetime">' . translate("Ajouter la date et l'heure") . '</label>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-12">
                    <input class="btn btn-primary" type="submit" value="' . translate("Sauvez votre journal") . '" />
                </div>
            </div>
        </form>';
    }
    
    /**
     * [savejournal description]
     *
     * @param   [type]  $uid       [$uid description]
     * @param   [type]  $journal   [$journal description]
     * @param   [type]  $datetime  [$datetime description]
     *
     * @return  [type]             [return description]
     */
    public function savejournal($uid, $journal, $datetime)
    {
        global $user;
    
        $cookie = cookiedecode($user);
    
        $result = sql_query("SELECT uid FROM users WHERE uname='$cookie[1]'");
        list($vuid) = sql_fetch_row($result);
    
        if ($uid == $vuid) {
            include("modules/upload/upload.conf.php");
    
            if ($DOCUMENTROOT == '') {
                global $DOCUMENT_ROOT;
                $DOCUMENTROOT = ($DOCUMENT_ROOT) ? $DOCUMENT_ROOT : $_SERVER['DOCUMENT_ROOT'];
            }
    
            $user_dir = $DOCUMENTROOT . $racine . "/storage/users_private/" . $cookie[1];
    
            if (!is_dir($user_dir)) {
                mkdir($user_dir, 0777);
                $fp = fopen($user_dir . '/index.html', 'w');
                fclose($fp);
                chmod($user_dir . '/index.html', 0644);
            }
    
            $journal = dataimagetofileurl($journal, 'storage/users_private/' . $cookie[1] . '/jou'); //
            $journal = removeHack(stripslashes(FixQuotes($journal)));
    
            if ($datetime) {
                $journalentry = $journal;
                $journalentry .= '<br /><br />';
    
                $journalentry .= date(translate("dateinternal"), time() + ((int) Config::get('npds.gmt') * 3600));
    
                sql_query("UPDATE users SET user_journal='$journalentry' WHERE uid='$uid'");
            } else {
                sql_query("UPDATE users SET user_journal='$journal' WHERE uid='$uid'");
            }

            Header("Location: user.php");
        } else {
            Header("Location: index.php");
        }
    }

}