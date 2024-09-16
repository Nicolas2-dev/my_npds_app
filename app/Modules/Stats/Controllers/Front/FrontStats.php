<?php

namespace App\Modules\Stats\Controllers\Front;

use App\Support\Facades\Stat;
use App\Modules\Npds\Core\FrontController;


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


    public function index()
    {
        

        $content = 'un contenue';

        $total = 2.98;

        // $dkn = sql_query("SELECT type, var, count FROM counter ORDER BY type DESC");

        // while (list($type, $var, $count) = sql_fetch_row($dkn)) {
        //     if (($type == "total") && ($var == "hits"))
        //         $total = $count;
        //     elseif ($type == "browser") {
        //         if ($var == "Netscape")
        //             $netscape = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "MSIE")
        //             $msie = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Konqueror")
        //             $konqueror = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Opera")
        //             $opera = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Lynx")
        //             $lynx = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "WebTV")
        //             $webtv = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Chrome")
        //             $chrome = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Safari")
        //             $safari = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Bot")
        //             $bot = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif (($type == "browser") && ($var == "Other"))
        //             $b_other = Stat::generatePourcentageAndTotal($count, $total);
        //     } elseif ($type == "os") {
        //         if ($var == "Windows")
        //             $windows = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Mac")
        //             $mac = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Linux")
        //             $linux = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "FreeBSD")
        //             $freebsd = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "SunOS")
        //             $sunos = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "IRIX")
        //             $irix = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "BeOS")
        //             $beos = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "OS/2")
        //             $os2 = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "AIX")
        //             $aix = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "Android")
        //             $andro = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif ($var == "iOS")
        //             $ios = Stat::generatePourcentageAndTotal($count, $total);
        //         elseif (($type == "os") && ($var == "Other"))
        //             $os_other = Stat::generatePourcentageAndTotal($count, $total);
        //     }
        // }

        // _vd($os_other);

        $this->title(__('Welcome'));

        $this->set('welcome_message', $content);
        $this->set('total', $total);

        // return $this->createView(compact('total'))
        //     ->shares('title', 'Homepage')
        //     ->with('content', $content);
    }
 

}