<?php

namespace Modules\Npds\Controllers\Api;

use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Support\Facades\Spam;
use App\Controllers\Core\BaseController;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Npds\Support\Facades\Language;


class ApiLanguage extends BaseController
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
    public static function manage()
    {
        $choice_user_language = Request::query('choice_user_language') ?: Request::post('choice_user_language');

        // Multi-language
        if (isset($choice_user_language)) {
            if ($choice_user_language != '') {

                $user_cook_duration = Config::get('npds.user_cook_duration');

                if ($user_cook_duration <= 0) {
                    $user_cook_duration = 1;
                }

                $timeX = time() + (3600 * $user_cook_duration);

                if ((stristr(Language::cache_list(), $choice_user_language)) and ($choice_user_language != ' ')) {
                    Cookie::set('user_language', $choice_user_language, $timeX);
                    
                    $user_language = $choice_user_language;
                }
            }
        }

        if (Config::get('npds.multi_langue')) {
            if (($user_language != '') and ($user_language != " ")) {

                $tmpML = stristr(Language::cache_list(), $user_language);
                $tmpML = explode(' ', $tmpML);

                if ($tmpML[0]) {
                    Config::set('npds.language', $tmpML[0]);
                }
            }
        }
    }

}
