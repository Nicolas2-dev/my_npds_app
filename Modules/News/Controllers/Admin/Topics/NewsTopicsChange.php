<?php

namespace Modules\News\Controllers\Admin;

use Modules\Npds\Support\Sanitize;
use Modules\Npds\Core\AdminController;


class NewsTopicsChange extends AdminController
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
    protected $hlpfile = 'topics';

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
    protected $f_meta_nom = 'topicsmanager';


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
        $this->f_titre = __d('news', 'Gestion des sujets');

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
     * @param [type] $topicid
     * @param integer $ok
     * @return void
     */
    public function topicchange($topicid, $topicname, $topicimage, $topictext, $topicadmin, $name, $url)
    {
        $topicadminX = explode(',', $topicadmin);
        array_pop($topicadminX);
    
        $res = sql_query("SELECT * FROM droits WHERE d_droits=11112 AND d_fon_fid=2");
    
        $d = array();
        $topad = array();
    
        while ($d = sql_fetch_row($res)) {
            $topad[] = $d[0];
        }
    
        foreach ($topicadminX as $value) {
            if (!in_array($value, $topad)) {
                sql_query("INSERT INTO droits VALUES ('$value', '2', '11112')");
            }
        }
    
        foreach ($topad as $value) { //pour chaque droit adminsujet on regarde le nom de l'adminsujet
            if (!in_array($value, $topicadminX)) { //si le nom de l'adminsujet n'est pas dans les nouveaux adminsujet
    
                //on cherche si il administre un autre sujet
                $resu =  mysqli_get_client_info() <= '8.0' 
                    ? sql_query("SELECT * FROM topics WHERE topicadmin REGEXP '[[:<:]]" . $value . "[[:>:]]'") 
                    : sql_query("SELECT * FROM topics WHERE topicadmin REGEXP '\\b" . $value . "\\b'");
    
                $nbrow = sql_num_rows($resu);
                list($tid) = sql_fetch_row($resu);
    
                if (($nbrow == 1) and ($topicid == $tid)) {
                    sql_query("DELETE FROM droits WHERE d_aut_aid='$value' AND d_droits=11112 AND d_fon_fid=2");
                }
            }
        }
    
        $topicname  = stripslashes(Sanitize::FixQuotes($topicname));
        $topicimage = stripslashes(Sanitize::FixQuotes($topicimage));
        $topictext  = stripslashes(Sanitize::FixQuotes($topictext));
        $name       = stripslashes(Sanitize::FixQuotes($name));
        $url        = stripslashes(Sanitize::FixQuotes($url));
    
        sql_query("UPDATE topics SET topicname='$topicname', topicimage='$topicimage', topictext='$topictext', topicadmin='$topicadmin' WHERE topicid='$topicid'");
        
        global $aid;
        Ecr_Log("security", "topicChange ($topicname, $topicid) by AID : $aid", "");
    
        if ($name) {
            sql_query("INSERT INTO related VALUES (NULL, '$topicid','$name','$url')");
        }
    
        Header("Location: admin.php?op=topicedit&topicid=$topicid");
    }

}
