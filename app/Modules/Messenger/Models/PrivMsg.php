<?php

namespace App\Modules\Messenger\Models;

use Npds\ORM\Model as BaseModel;

/**
 * [UserStatus description]
 */
class PrivMsg extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'priv_msgs';

    /**
     * [$relations description]
     *
     * @var [type]
     */
    protected $relations = array('user');

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
     * [user description]
     *
     * @return  [type]  [return description]
     */
    public function user()
    {
        return $this->belongsTo('App\Modules\Users\Models\User', 'id');
    }

}