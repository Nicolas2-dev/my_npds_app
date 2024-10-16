<?php

namespace Modules\Pollbooth\Library;

use Npds\Config\Config;
use Modules\Npds\Support\Facades\Language;
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
     * \Modules\Pollbooth\Boxe => pollMain($ibid, $pollClose)
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

            if ($ibid) {
                pollMain($ibid, $pollClose);
            }

        } elseif ($result = sql_query("SELECT pollID FROM poll_data ORDER BY pollID DESC LIMIT 1")) {
            list($pollID) = sql_fetch_row($result);
            list($ibid, $pollClose) = $this->pollSecur($pollID);

            if ($ibid) {
                pollMain($ibid, $pollClose);
            }
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

    // Front Controller

    /**
     * Undocumented function
     *
     * @param [type] $pollID
     * @param [type] $voteID
     * @param [type] $forwarder
     * @return void
     */
    public function pollCollector($pollID, $voteID, $forwarder)
    {
        // Specified the index and the name of the application for the table appli_log
        $al_id = 1;
        $al_nom = 'Poll';

        if ($voteID) {
            global $al_id, $al_nom;

            $voteValid = "1";

            $result = sql_query("SELECT timeStamp FROM poll_desc WHERE pollID='$pollID'");
            list($timeStamp) = sql_fetch_row($result);

            $cookieName = 'poll' .  $timeStamp;

            global $$cookieName;

            if ($$cookieName == "1") {
                $voteValid = "0";
            } else {
                setcookie("$cookieName", "1", time() + 86400);
            }

            global $user;
            if ($user) {
                global $cookie;

                $user_req = "OR al_uid='$cookie[0]'";
            } else {
                $cookie[0] = "1";
                $user_req = '';
            }

            if (Config::get('npds.setCookies') == "1") {
                $ip = getip();

                if (Config::get('npds.dns_verif')) {
                    $hostname = "OR al_hostname='" . @gethostbyaddr($ip) . "' ";
                } else {
                    $hostname = "";
                }

                $sql = "SELECT al_id FROM appli_log WHERE al_id='$al_id' AND al_subid='$pollID' AND (al_ip='$ip' " . $hostname . $user_req . ")";
                
                if ($result = sql_fetch_row(sql_query($sql))) {
                    $voteValid = "0";
                }
            }

            if ($voteValid == "1") {
                $ip = getip();
                $hostname = Config::get('npds.dns_verif') ? @gethostbyaddr($ip) : '';

                sql_query("INSERT INTO appli_log (al_id, al_name, al_subid, al_date, al_uid, al_data, al_ip, al_hostname) VALUES ('$al_id', '$al_nom', '$pollID', now(), '$cookie[0]', '$voteID', '$ip', '$hostname')");
                sql_query("UPDATE poll_data SET optionCount=optionCount+1 WHERE (pollID='$pollID') AND (voteID='$voteID')");
                sql_query("UPDATE poll_desc SET voters=voters+1 WHERE pollID='$pollID'");
            }
        }

        Header("Location: $forwarder");
    }

    /**
     * Undocumented function
     *
     * @param integer $pollID
     * @return void
     */
    public function pollResults(int $pollID): void
    {
        if (!isset($pollID) or empty($pollID)) {
            $pollID = 1;
        }

        $result = sql_query("SELECT pollID, pollTitle, timeStamp FROM poll_desc WHERE pollID='$pollID'");
        list(, $pollTitle) = sql_fetch_row($result);

        echo '
        <h3 class="my-3">' . $pollTitle . '</h3>';

        $result = sql_query("SELECT SUM(optionCount) AS SUM FROM poll_data WHERE pollID='$pollID'");
        list($sum) = sql_fetch_row($result);

        echo '
        <h4><span class="badge bg-secondary">' . $sum . '</span>&nbsp;' . __d('pollbooth', 'Résultats') . '</h4>';

        for ($i = 1; $i <= Config::get('npds.maxOptions'); $i++) {
            $result = sql_query("SELECT optionText, optionCount, voteID FROM poll_data WHERE (pollID='$pollID') AND (voteID='$i')");
            $object = sql_fetch_assoc($result);
            
            if (!is_null($object)) {
                $optionText = $object['optionText'];
                $optionCount = $object['optionCount'];
            } else {
                $optionText = '';
                $optionCount = 0;
            }

            if ($optionText != "") {
                if ($sum) {
                    $percent = 100 * $optionCount / $sum;
                    $percentInt = (int)$percent;
                } else {
                    $percentInt = 0;
                }

                echo '
                <div class="row">
                    <div class="col-sm-5 mt-3">' . Language::aff_langue($optionText) . '</div>
                    <div class="col-sm-7">
                        <span class="badge bg-secondary mb-1">' . wrh($optionCount) . '</span>
                            <div class="progress">
                            <span class="progress-bar" role="progressbar" aria-valuenow="' . $percentInt . '%" aria-valuemin="0" aria-valuemax="100" style="width:' . $percentInt . '%;" title="' . $percentInt . '%" data-bs-toggle="tooltip"></span>
                            </div>
                    </div>
                </div>';
            }
        }

        echo '<br />';
        echo '<p class="text-center"><b>' . __d('pollbooth', 'Nombre total de votes: ') . ' ' . $sum . '</b></p><br />';

        if (Config::get('npds.setCookies') > 0) {
            echo '<p class="text-danger">' . __d('pollbooth', 'Un seul vote par sondage.') . '</p>';
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $pollID
     * @param [type] $pollClose
     * @return void
     */
    public function pollboxbooth($pollID, $pollClose)
    {
        global $boxTitle, $boxContent;

        if (!isset($pollID)) {
            $pollID = 1;
        }

        if (!isset($url)) {
            $url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
        }

        $boxContent = '
        <form action="pollBooth.php" method="post">
            <input type="hidden" name="pollID" value="' . $pollID . '" />
            <input type="hidden" name="forwarder" value="' . $url . '" />';

        $result = sql_query("SELECT pollTitle, voters FROM poll_desc WHERE pollID='$pollID'");
        list($pollTitle, $voters) = sql_fetch_row($result);

        global $block_title;
        $boxTitle = $block_title == '' ? __d('pollbooth', 'Sondage') : $block_title;

        $boxContent .= '<h4>' . Language::aff_langue($pollTitle) . '</h4>';

        $result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM poll_data WHERE (pollID='$pollID' AND optionText<>'') ORDER BY voteID");
        
        $sum = 0;
        $j = 0;

        if (!$pollClose) {
            $boxContent .= '
                <div class="custom-controls-stacked">';

            while ($object = sql_fetch_assoc($result)) {
                $boxContent .= '
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="voteID' . $j . '" name="voteID" value="' . $object['voteID'] . '" />
                    <label class="form-check-label" for="voteID' . $j . '">' . Language::aff_langue($object['optionText']) . '</label>
                </div>';
                $sum = $sum + $object['optionCount'];
                $j++;
            }

            $boxContent .= '
                </div>
                <div class="clearfix"></div>';
        } else {
            while ($object = sql_fetch_assoc($result)) {
                $boxContent .= "&nbsp;" . Language::aff_langue($object['optionText']) . "<br />\n";
                $sum = $sum + $object['optionCount'];
            }
        }

        if (!$pollClose) {
            $inputvote = '
            <button class="btn btn-primary btn-sm my-2" type="submit" value="' . __d('pollbooth', 'Voter') . '" title="' . __d('pollbooth', 'Voter') . '" />' . __d('pollbooth', 'Voter') . '</button>';
        }

        $boxContent .= '
                <div class="mb-3">' . $inputvote . '</div>
        </form>';

        $boxContent .= '<div><ul><li><a href="pollBooth.php">' . __d('pollbooth', 'Anciens sondages') . '</a></li>';

        if (Config::get('npds.pollcomm')) {
            if (file_exists("modules/comments/config/pollBoth.conf.php")) {
                include("modules/comments/config/pollBoth.conf.php");
            }

            list($numcom) = sql_fetch_row(sql_query("SELECT COUNT(*) FROM posts WHERE forum_id='$forum' AND topic_id='$pollID' AND post_aff='1'"));
            $boxContent .= '<li>' . __d('pollbooth', 'Votes : ') . ' ' . $sum . '</li><li>' . __d('pollbooth', 'Commentaire(s) : ') . ' ' . $numcom . '</li>';
        } else {
            $boxContent .= '<li>' . __d('pollbooth', 'Votes : ') . ' ' . $sum . '</li>';
        }

        $boxContent .= '</ul></div>';

        echo '<div class="card card-body">' . $boxContent . '</div>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $pollID
     * @return void
     */
    public function PollMain_aff($pollID)
    {
        echo '<p><strong><a href="pollBooth.php">' . __d('pollbooth', 'Anciens sondages') . '</a></strong></p>';
    }

}
