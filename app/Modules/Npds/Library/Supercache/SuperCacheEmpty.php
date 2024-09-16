<?php

namespace App\Modules\Npds\Library\Supercache;

use App\Modules\Npds\Contracts\SuperCacheEmptyInterface;


class SuperCacheEmpty implements SuperCacheEmptyInterface
{

    /**
     * [$genereting_output description]
     *
     * @var [type]
     */
    protected $genereting_output;
   

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
    // public static function getInstance()
    // {
    //     if (isset(static::$instance)) {
    //         return static::$instance;
    //     }

    //     return static::$instance = new static();
    // }
    
    /**
     * [get_Genereting_Output description]
     *
     * @return  [type]  [return description]
     */
    public function get_Genereting_Output()
    {
        return $this->genereting_output;
    }

    /**
     * [set_Genereting_Output description]
     *
     * @param   [type]  $output  [$output description]
     *
     * @return  [type]           [return description]
     */
    public function set_Genereting_Output($output)
    {
        $this->genereting_output = $output;
    }

}
