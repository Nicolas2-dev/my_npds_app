<?php

namespace App\Modules\Download\Controllers\Front;

use App\Modules\Npds\Core\FrontController;

/**
 * Undocumented class
 */
class DownloadBroken extends FrontController
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
     * @param [type] $did
     * @return void
     */
    public function broken($did)
    {
        global $user, $cookie;
    
        settype($did, 'integer');
    
        if ($user) {
            if ($did) {

                settype($did, "integer");
    
                $message = Config::get('npds.nuke_url') . "\n" . __d('download', 'Téléchargements') . " ID : $did\n" . __d('download', 'Auteur') . " $cookie[1] / IP : " . getip() . "\n\n";
    
                send_email(Config::get('npds.notify_email'), html_entity_decode(__d('download', 'Rapporter un lien rompu'), ENT_COMPAT | ENT_HTML401, cur_charset), nl2br($message), Config::get('npds.notify_from'), false, "html", '');
    
                echo '
                <div class="alert alert-success">
                <p class="lead">' . __d('download', 'Pour des raisons de sécurité, votre nom d\'utilisateur et votre adresse IP vont être momentanément conservés.') . '<br />' . __d('download', 'Merci pour cette information. Nous allons l\'examiner dès que possible.') . '</p>
                </div>';
    
            } else
                Header("Location: download.php");
        } else
            Header("Location: download.php");
    }

}
