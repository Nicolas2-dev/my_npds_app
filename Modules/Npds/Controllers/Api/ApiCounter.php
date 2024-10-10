<?php

namespace Modules\Npds\Controllers\Api;

use Npds\Config\Config;
use Npds\Support\Facades\DB;
use App\Controllers\Core\BaseController;
use Modules\Npds\Support\Facades\Auth;


class ApiCounter extends BaseController
{

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $autoRender = false;


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
    public static function update()
    {
        if ((!Auth::guard('admin')) or (Config::get('npds.not_admin_count') != 1)) {
            $user_agent = getenv("HTTP_USER_AGENT");
        
            if ((stristr($user_agent, "Nav")) 
            || (stristr($user_agent, "Gold")) 
            || (stristr($user_agent, "X11")) 
            || (stristr($user_agent, "Mozilla")) 
            || (stristr($user_agent, "Netscape")) 
            and (!stristr($user_agent, "MSIE")) 
            and (!stristr($user_agent, "SAFARI")) 
            and (!stristr($user_agent, "IPHONE")) 
            and (!stristr($user_agent, "IPOD")) 
            and (!stristr($user_agent, "IPAD")) 
            and (!stristr($user_agent, "ANDROID"))) 
                $browser = "Netscape";
            elseif (stristr($user_agent, "MSIE")) 
                $browser = "MSIE";
            elseif (stristr($user_agent, "Trident")) 
                $browser = "MSIE";
            elseif (stristr($user_agent, "Lynx")) 
                $browser = "Lynx";
            elseif (stristr($user_agent, "Opera")) 
                $browser = "Opera";
            elseif (stristr($user_agent, "WebTV")) 
                $browser = "WebTV";
            elseif (stristr($user_agent, "Konqueror")) 
                $browser = "Konqueror";
            elseif (stristr($user_agent, "Chrome")) 
                $browser = "Chrome";
            elseif (stristr($user_agent, "Safari")) 
                $browser = "Safari";
            elseif (preg_match('#([bB]ot|[sS]pider|[yY]ahoo)#', $user_agent)) 
                $browser = "Bot";
            else 
                $browser = "Other";
        
            if (stristr($user_agent, "Win")) 
                $os = "Windows";
            elseif ((stristr($user_agent, "Mac")) || (stristr($user_agent, "PPC"))) 
                $os = "Mac";
            elseif (stristr($user_agent, "Linux")) 
                $os = "Linux";
            elseif (stristr($user_agent, "FreeBSD")) 
                $os = "FreeBSD";
            elseif (stristr($user_agent, "SunOS")) 
                $os = "SunOS";
            elseif (stristr($user_agent, "IRIX")) 
                $os = "IRIX";
            elseif (stristr($user_agent, "BeOS")) 
                $os = "BeOS";
            elseif (stristr($user_agent, "OS/2")) 
                $os = "OS/2";
            elseif (stristr($user_agent, "AIX")) 
                $os = "AIX";
            else 
                $os = "Other";
        
            $data = array(
                'count' => DB::raw('count+1')
            );

            DB::table('counter')
            		->where('type', 'total')
                    ->where('var', 'hits')
                    ->orwhere('var', $browser)
                    ->orwhere('var', $os)
                    ->update($data);

        }
    }

}
