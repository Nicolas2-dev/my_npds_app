<?php

namespace Modules\Authors\Controllers\Admin;

use Modules\Authors\Models\Author;
use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Password;
use Modules\Authors\Support\Facades\Author as L_Author;

class AuthorAdd extends AdminController
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
    protected $hlpfile = "authors";

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
    protected $f_meta_nom = 'mod_authors';


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
        $this->f_titre = __d('authors', 'Administrateurs');

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
    public function UpdateAuthor()
    {
        settype($add_radminsuper, 'int');
        
        if (!($add_aid && $add_name && $add_email && $add_pwd)) {
            echo L_author::error_handler(__d('authors', 'Vous devez remplir tous les Champs') . '<br />');
        }

        include_once('functions.php');

        if (checkdnsmail($add_email) === false) {
            echo L_Author::error_handler(__d('authors', 'ERREUR : DNS ou serveur de mail incorrect') . '<br />');
        }

        $AlgoCrypt = PASSWORD_BCRYPT;
        $min_ms = 100;
        $options = ['cost' => Password::getOptimalBcryptCostParameter($add_pwd, $AlgoCrypt, $min_ms)];
        $hashpass = password_hash($add_pwd, $AlgoCrypt, $options);
        $add_pwdX = crypt($add_pwd, $hashpass);

        $result = sql_query("INSERT INTO authors VALUES ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwdX', '1', '0', '$add_radminsuper')");
        Author::updatedroits($add_aid);

        // Copie du fichier pour filemanager
        if ($add_radminsuper or isset($ad_d_27)) // $ad_d_27 pas là ?
            @copy("modules/f-manager/users/modele.admin.conf.php", "modules/f-manager/users/" . strtolower($add_aid) . ".conf.php");

        global $aid;
        Ecr_Log('security', "AddAuthor($add_aid) by AID : $aid", '');

        Header("Location: admin.php?op=mod_authors");
    }
    
}
