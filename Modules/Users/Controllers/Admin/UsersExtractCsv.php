<?php

namespace Modules\Users\Controllers\Admin;

use Modules\Npds\Core\AdminController;
use Modules\Npds\Support\Facades\Mailer;


class Users extends AdminController
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
    protected $hlpfile = 'users';

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
    protected $f_meta_nom = 'mod_users';


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
        $this->f_titre = __d('users', 'Edition des Utilisateurs');

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
    public function extractUserCSV()
    {
        $MSos = get_os();

        if ($MSos) {
            $crlf = "\r\n";
        } else {
            $crlf = "\n";
        }
    
        $deliminator = ';';
        $line = "UID;UNAME;NAME;URL;EMAIL;FEMAIL;C1;C2;C3;C4;C5;C6;C7;C8;M1;M2;T1;T2" . $crlf;
    
        $result = sql_query("SELECT uid, uname, name, url, email, femail FROM users WHERE uid!='1' ORDER BY uid");
    
        while ($temp_user = sql_fetch_row($result)) {
            foreach ($temp_user as $val) {
    
                $val = str_replace("\r\n", "\n", $val);
    
                if (preg_match("#[$deliminator\"\n\r]#", $val)) {
                    $val = '"' . str_replace('"', '""', $val) . '"';
                }
    
                $line .= $val . $deliminator;
            }
    
            $result2 = sql_query("SELECT C1, C2, C3, C4, C5, C6, C7, C8, M1, M2, T1, T2 FROM users_extend WHERE uid='$temp_user[0]'");
            $temp_user2 = sql_fetch_row($result2);
    
            if ($temp_user2) {
                foreach ($temp_user2 as $val2) {
                    $val2 = str_replace("\r\n", "\n", $val2);
    
                    if (preg_match("#[$deliminator\"\n\r]#", $val2)) {
                        $val2 = '"' . str_replace('"', '""', $val2) . '"';
                    }
    
                    $line .= $val2 . $deliminator;
                }
            }
    
            $line = substr($line, 0, (strlen($deliminator) * -1));
            $line .= $crlf;
        }
    
        Mailer::send_file($line, "annuaire", "csv", $MSos);
    
        global $aid;
        Ecr_Log('security', "ExtractUserCSV() by AID : $aid", '');
    }

}
