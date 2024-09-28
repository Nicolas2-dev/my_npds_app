<?php

namespace App\Modules\Stats\Controllers\Front;

use App\Modules\Npds\Core\FrontController;
use App\Modules\Stats\Support\Facades\Stat;


class FrontStats extends FrontController
{

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }


    protected function before()
    {
        // Leave to parent's method the Flight decisions.
        return parent::before();
    }


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
        $dkn = sql_query("SELECT type, var, count FROM counter ORDER BY type DESC");

        while (list($type, $var, $count) = sql_fetch_row($dkn)) {

            if (($type == "total") && ($var == "hits"))
                $total = $count;
            elseif ($type == "browser") {
                if ($var == "Netscape")
                    $netscape = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "MSIE")
                    $msie = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Konqueror")
                    $konqueror = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Opera")
                    $opera = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Lynx")
                    $lynx = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "WebTV")
                    $webtv = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Chrome")
                    $chrome = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Safari")
                    $safari = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Bot")
                    $bot = Stat::generatePourcentageAndTotal($count, $total);
                elseif (($type == "browser") && ($var == "Other"))
                    $b_other = Stat::generatePourcentageAndTotal($count, $total);
            } elseif ($type == "os") {
                if ($var == "Windows")
                    $windows = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Mac")
                    $mac = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Linux")
                    $linux = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "FreeBSD")
                    $freebsd = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "SunOS")
                    $sunos = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "IRIX")
                    $irix = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "BeOS")
                    $beos = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "OS/2")
                    $os2 = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "AIX")
                    $aix = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "Android")
                    $andro = Stat::generatePourcentageAndTotal($count, $total);
                elseif ($var == "iOS")
                    $ios = Stat::generatePourcentageAndTotal($count, $total);
                elseif (($type == "os") && ($var == "Other"))
                    $os_other = Stat::generatePourcentageAndTotal($count, $total);
            }
        }

        $this->title(__('Welcome'));

        $this->set('total',     $total);
        $this->set('netscape',  $netscape);
        $this->set('msie',      $msie);
        $this->set('konqueror', $konqueror);
        $this->set('opera',     $opera);
        $this->set('lynx',      $lynx);
        $this->set('webtv',     $webtv);
        $this->set('chrome',    $chrome);
        $this->set('safari',    $safari);
        $this->set('bot',       $bot);
        $this->set('b_other',   $b_other);
        $this->set('windows',   $windows);
        $this->set('mac',       $mac);
        $this->set('linux',     $linux);
        $this->set('freebsd',   $freebsd);
        $this->set('sunos',     $sunos);
        $this->set('irix',      $irix);
        $this->set('beos',      $beos);
        $this->set('os2',       $os2);
        $this->set('aix',       $aix);
        $this->set('andro',     $andro);
        $this->set('ios',       $ios);
        $this->set('os_other',  $os_other);


        $this->set('unum',  10);
        $this->set('gnum',  10);
        $this->set('anum',  10);
        $this->set('tnum',  10);
        $this->set('cnum',  10);
        $this->set('snum',  10);
        $this->set('secnum',  10);
        $this->set('secanum',  10);
        $this->set('links',  10);
        $this->set('cat',  10);
        $this->set('subnum',  10);
    }
    
}
