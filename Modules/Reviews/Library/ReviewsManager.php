<?php

namespace Modules\Reviews\Library;

use Modules\Reviews\Contracts\ReviewsInterface;

/**
 * Undocumented class
 */
class ReviewsManager implements ReviewsInterface 
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
