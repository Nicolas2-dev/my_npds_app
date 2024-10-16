<?php

namespace Modules\Pollbooth\Controllers\Front;

use Modules\Npds\Core\FrontController;
use Modules\Npds\Support\Facades\Language;


class PollboothList extends FrontController
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
    public function pollList()
    {
        $result = sql_query("SELECT pollID, pollTitle, voters FROM poll_desc ORDER BY timeStamp");

        echo '
        <h2 class="mb-3">' . __d('pollbooth', 'Sondage') . '</h2>
        <hr />
        <div class="row">';

        while ($object = sql_fetch_assoc($result)) {

            $id = $object['pollID'];
            $pollTitle = $object['pollTitle'];
            $voters = $object['voters'];

            $result2 = sql_query("SELECT SUM(optionCount) AS SUM FROM poll_data WHERE pollID='$id'");
            list($sum) = sql_fetch_row($result2);

            echo '
            <div class="col-sm-8">' . Language::aff_langue($pollTitle) . '</div>
            <div class="col-sm-4 text-end">(<a href="pollBooth.php?op=results&amp;pollID=' . $id . '">' . __d('pollbooth', 'Résultats') . '</a> - ' . $sum . ' ' . __d('pollbooth', 'votes') . ')</div>';
        }

        echo '
        </div>';
    }

}