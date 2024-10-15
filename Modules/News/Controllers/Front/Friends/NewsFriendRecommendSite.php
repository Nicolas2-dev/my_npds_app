<?php

namespace Modules\News\Controllers\Front;

use Modules\Npds\Core\FrontController;


/**
 * Undocumented class
 */
class NewsFriendRecommendSite extends FrontController
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
    public function RecommendSite()
    {
        global $user;
    
        if ($user) {
            global $cookie;
    
            $result = sql_query("SELECT name, email FROM users WHERE uname='$cookie[1]'");
            list($yn, $ye) = sql_fetch_row($result);
        } else {
            $yn = '';
            $ye = '';
        }
    
        echo '
        <div class="card card-body">
        <h2>' . __d('news', 'Recommander ce site à un ami') . '</h2>
        <hr />
        <form id="friendrecomsite" action="friend.php" method="post">
            <input type="hidden" name="op" value="SendSite" />
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="yname" name="yname" value="' . $yn . '" required="required" maxlength="100" />
                <label for="yname">' . __d('news', 'Votre nom') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_yname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="ymail" name="ymail" value="' . $ye . '" required="required" maxlength="100" />
                <label for="ymail">' . __d('news', 'Votre Email') . '</label>
            </div>
            <span class="help-block text-end"><span class="muted" id="countcar_ymail"></span></span>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="fname" name="fname" required="required" maxlength="100" />
                <label for="fname">' . __d('news', 'Nom du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fname"></span></span>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="fmail" name="fmail" required="required" maxlength="100" />
                <label for="fmail">' . __d('news', 'Email du destinataire') . '</label>
                <span class="help-block text-end"><span class="muted" id="countcar_fmail"></span></span>
            </div>
            ' . Q_spambot() . '
            <div class="mb-3 row">
                <div class="col-sm-8 ms-sm-auto">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-lg fa-at"></i>&nbsp;' . __d('news', 'Envoyer') . '</button>
                </div>
            </div>
        </form>';
    
        $arg1 = '
        var formulid = ["friendrecomsite"];
        inpandfieldlen("yname",100);
        inpandfieldlen("ymail",100);
        inpandfieldlen("fname",100);
        inpandfieldlen("fmail",100);';
    
        adminfoot('fv', '', $arg1, '');
    }

}
