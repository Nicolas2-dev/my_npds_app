<?php

namespace App\Modules\Authors\Models;

use Npds\ORM\Model as BaseModel;


/**
 * [User description]
 */
class Author extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'authors';

    /**
     * [$relations description]
     *
     * @var [type]
     */
    protected $relations = array();

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        parent::__construct();
    }

}
