<?php

namespace Modules\News\Controllers\Front;

use Modules\Npds\Core\FrontController;


/**
 * Undocumented class
 */
class NewsFriend extends FrontController
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
     * @param [type] $sid
     * @param [type] $archive
     * @return void
     */
    public function FriendSend($sid, $archive)
    {
        $result = sql_query("SELECT title, aid FROM stories WHERE sid='$sid'");
        list($title, $aid) = sql_fetch_row($result);
    
        if (!$aid)
            header("Location: index.php");
    
        // include("header.php");
    
        echo '
        <div class="card card-body">
        <h2><i class="fa fa-at fa-lg text-muted"></i>&nbsp;' . __d('news', 'Envoi de l\'article à un ami') . '</h2>
        <hr />
        <p class="lead">' . __d('news', 'Vous allez envoyer cet article') . ' : <strong>' . aff_langue($title) . '</strong></p>
        <form id="friendsendstory" action="friend.php" method="post">
            <input type="hidden" name="sid" value="' . $sid . '" />';
    
        global $user;
    
        $yn = '';
        $ye = '';
    
        if ($user) {
            global $cookie;
    
            $result = sql_query("SELECT name, email FROM users WHERE uname='$cookie[1]'");
            list($yn, $ye) = sql_fetch_row($result);
        }
    
        echo '
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fname" name="fname" maxlength="100" required="required" />
                <label for="fname">' . __d('news', 'Nom du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="fmail" name="fmail" maxlength="254" required="required" />
                <label for="fmail">' . __d('news', 'Email du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fmail"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="yname" name="yname" value="' . $yn . '" maxlength="100" required="required" />
                <label for="yname">' . __d('news', 'Votre nom') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_yname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="ymail" name="ymail" value="' . $ye . '" maxlength="254" required="required" />
                <label for="ymail">' . __d('news', 'Votre Email') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_ymail"></span></span>
            </div>';
    
        echo '' . Q_spambot();
    
        echo '
            <input type="hidden" name="archive" value="' . $archive . '" />
            <input type="hidden" name="op" value="SendStory" />
            <button type="submit" class="btn btn-primary" title="' . __d('news', 'Envoyer') . '"><i class="fa fa-lg fa-at"></i>&nbsp;' . __d('news', 'Envoyer') . '</button>
        </form>';
    
        $arg1 = '
        var formulid = ["friendsendstory"];
        inpandfieldlen("yname",100);
        inpandfieldlen("ymail",254);
        inpandfieldlen("fname",100);
        inpandfieldlen("fmail",254);';
    
        adminfoot('fv', '', $arg1, '');
    }

}
