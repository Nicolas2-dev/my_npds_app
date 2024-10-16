<?php

namespace Modules\Pollbooth\Controllers\Front;

use Modules\Npds\Core\FrontController;
use Modules\Pollbooth\Support\Facades\Pollbooth as LPollbooth;


class Pollbooth extends FrontController
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
    public function index()
    {
        global $header;

        $header = 0;
        
        if (!isset($pollID)) {
            // include('header.php');
            LPollbooth::pollList();
        }
        
        settype($op, 'string');
        
        if (isset($forwarder)) {
            if (isset($voteID)) {
                LPollbooth::pollCollector($pollID, $voteID, $forwarder);
            } else {
                Header("Location: $forwarder");
            }
        
        } elseif ($op == 'results') {
            list($ibid, $pollClose) = LPollbooth::pollSecur($pollID);
        
            if ($pollID == $ibid) {
                // if ($header != 1) {
                //     include("header.php");
                // }
        
                echo '<h2>' . __d('pollbooth', 'Sondage') . '</h2><hr />';
        
                LPollbooth::pollResults($pollID);
        
                if (!$pollClose) {
                    $block_title = '<h3>' . __d('pollbooth', 'Voter') . '</h3>';
                    echo $block_title;
        
                    LPollbooth::pollboxbooth($pollID, $pollClose);
                } else {
                    LPollbooth::PollMain_aff();
                }
        
                if (Config::get('npds.pollcomm')) {
                    if (file_exists("modules/comments/config/pollBoth.conf.php")) {
                        include("modules/comments/config/pollBoth.conf.php");
        
                        if ($pollClose == 99) {
                            $anonpost = 0;
                        }
        
                        include("modules/comments/comments.php");
                    }
                }
            } else {
                Header("Location: $forwarder");
            }
        }
        
        // include('footer.php');
    }

}