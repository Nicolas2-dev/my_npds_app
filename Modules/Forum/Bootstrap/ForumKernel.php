<?php

namespace Modules\Forum\Bootstrap;

use Npds\Http\Request;
use Npds\Config\Config;
use Npds\Cookie\Cookie;
use Npds\Support\Facades\DB;
/**
 * Undocumented class
 */
class ForumKernel
{

    /**
     * 
     */
    protected static $module_path;

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $aliases = [
        'Forum'         => 'Modules\Forum\Support\Facades\Forum',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public static $boot_method = [
        'config_forum',
        'config_allow_upload_forum',
        'user_forum_access_create_cookie',
        // 'test'
    ];


    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * Undocumented function
     */
    public function __construct($directory)
    {
        static::$module_path = $directory;
    }

    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance($directory)
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static($directory);
    }
    
    /**
     * Chargement de la configuration des forums
     */
    public function register_config_forum()
    {
        if ($rowQ1 = DBQ_Select(DB::table('config')->select('*')->get(), 3600)) {
            foreach ($rowQ1[0] as $key => $value) {
                Config::set('forum.config.'. $key, $value);
            }
        }
    }

    /**
     * on verifie que l'upload et bien actif pour le forum, sinon on modifie la config a 0
     */
    public function register_config_allow_upload_forum(Request $request)
    {
        if (Config::get('forum.config.allow_upload_forum')) {
            if ($rowQ1 = DBQ_Select(DB::table('forums')->select('attachement')->where('forum_id', $request->query('forum'))->get(), 3600)) {
                foreach ($rowQ1[0] as $value) {
                    Config::set('forum.config.allow_upload_forum', $value);
                }
            }
        } 
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function register_test(Request $request, ForumKernel $kernel)
    {
        // dump(
        //     Config::get('forum.config'), 
        //     Config::get('forum.config.allow_upload_forum')
        // );

        echo $kernel->good();

        echo 'good !!';
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function good()
    {
        echo ('vraimen good ce truc !!!');
    }

    /**
     * si le forum est prive ont check le password et on cree le cookie de l'utilisateur
     *
     * @return void
     */
    public function register_user_forum_access_create_cookie(Request $request)
    {
        if ($rowQ1 = DBQ_Select(DB::table('forums')->select('forum_pass')->where('forum_id', $request->query('forum'))->where('forum_type', 1)->get(), 3600)) {

            $Forum_Priv = Cookie::get('Forum_Priv'.$request->query('forum'));

            if (isset($Forum_Priv)) {
                $Xpasswd = base64_decode($Forum_Priv);
        
                foreach ($rowQ1[0] as $value) {
                    $forum_xpass = $value;
                }
        
                if (md5($forum_xpass) == $Xpasswd) {
                    $Forum_passwd = $forum_xpass;
                } else {
                    Cookie::set('Forum_Priv'.$request->query('forum'), '', 0);
                }
            } else {

                $Forum_passwd = $request->post('Forum_passwd');

                if (isset($Forum_passwd)) {
                    foreach ($rowQ1[0] as $value) {
                        if ($value == $Forum_passwd) {
                            Cookie::set('Forum_Priv'.$request->query('forum'), base64_encode(md5($Forum_passwd)), time() + 900);
                        }
                    }
                }
            }
        }
    }

}
