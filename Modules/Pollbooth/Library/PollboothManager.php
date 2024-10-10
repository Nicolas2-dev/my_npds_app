<?php

namespace Modules\Pollbooth\Library;

use Modules\Pollbooth\Contracts\PollboothInterface;


class PollboothManager implements PollboothInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [PollNewest description]
     *
     * @param   int   $id  [$id description]
     *
     * @return  void       [return description]
     */
    public function PollNewest(int $id = null): void
    {
        if ($id != 0) {
            settype($id, "integer");
            list($ibid, $pollClose) = $this->pollSecur($id);

            if ($ibid) 
                pollMain($ibid, $pollClose);

        } elseif ($result = sql_query("SELECT pollID FROM poll_data ORDER BY pollID DESC LIMIT 1")) {
            list($pollID) = sql_fetch_row($result);
            list($ibid, $pollClose) = $this->pollSecur($pollID);

            if ($ibid) 
                pollMain($ibid, $pollClose);
        }
    }

    /**
     * [pollSecur description]
     *
     * @param   [type]  $pollID  [$pollID description]
     *
     * @return  [type]           [return description]
     */
    public function pollSecur($pollID)
    {
        global $user;

        $pollClose = '';

        $result = sql_query("SELECT pollType FROM poll_data WHERE pollID='$pollID'");

        if (sql_num_rows($result)) {
            list($pollType) = sql_fetch_row($result);

            $pollClose = (($pollType / 128) >= 1 ? 1 : 0);
            $pollType = $pollType % 128;

            if (($pollType == 1) and !isset($user)) {
                $pollClose = 99;
            }
        }

        return array($pollID, $pollClose);
    }

}
