<?php

namespace Modules\News\Controllers\Admin;

use Npds\Config\Config;
use Modules\Npds\Support\Sanitize;
use Modules\News\Support\Facades\News;
use Modules\Npds\Core\AdminController;


class NewsAutomatedSave extends AdminController
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
    protected $hlpfile = 'automated';

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
    protected $f_meta_nom = 'autoStory';


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
        $this->f_titre = __d('news', 'Editer un Article');;

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
     * @param [type] $anid
     * @param [type] $title
     * @param [type] $hometext
     * @param [type] $bodytext
     * @param [type] $topic
     * @param [type] $notes
     * @param [type] $catid
     * @param [type] $ihome
     * @param [type] $informant
     * @param [type] $members
     * @param [type] $Mmembers
     * @param [type] $date_debval
     * @param [type] $date_finval
     * @param [type] $epur
     * @return void
     */
    public function autoSaveEdit($anid, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome, $informant, $members, $Mmembers, $date_debval, $date_finval, $epur)
    {
        $date_debval = !isset($date_debval) ? $dd_pub . ' ' . $dh_pub . ':01' : $date_debval;
        $date_finval = !isset($date_finval) ? $fd_pub . ' ' . $fh_pub . ':01' : $date_finval;

        if ($date_finval < $date_debval) {
            $date_finval = $date_debval;
        }

        $title      = stripslashes(Sanitize::FixQuotes(str_replace('"', '&quot;', $title)));
        $hometext   = stripslashes(Sanitize::FixQuotes($hometext));
        $bodytext   = stripslashes(Sanitize::FixQuotes($bodytext));
        $notes      = stripslashes(Sanitize::FixQuotes($notes));
    
        if (($members == 1) and ($Mmembers == '')) {
            $ihome = '-127';
        }
    
        if (($members == 1) and (($Mmembers > 1) and ($Mmembers <= 127))) {
            $ihome = $Mmembers;
        }
    
        sql_query("UPDATE autonews SET catid='$catid', title='$title', time=now(), hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome', date_debval='$date_debval', date_finval='$date_finval', auto_epur='$epur' WHERE anid='$anid'");
        
        if (Config::get('npds.ultramode')) {
            News::ultramode();
        }
    
        Header("Location: admin.php?op=autoEdit&anid=$anid");
    }

}
