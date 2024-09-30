<?php

namespace App\Modules\Users\Library;

use Npds\view\View;
use Npds\Http\Request;
use App\Modules\Users\Contracts\UserPopoverInterface;

/**
 * Undocumented class
 */
class UserPopoverManager implements UserPopoverInterface 
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
    


}
