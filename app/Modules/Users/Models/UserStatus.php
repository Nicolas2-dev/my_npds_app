<?php

namespace App\Modules\Users\Models;

use Npds\ORM\Model as BaseModel;

/**
 * [UserStatus description]
 */
class UserStatus extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'users_status';

    /**
     * [$relations description]
     *
     * @var [type]
     */
    protected $relations = array('user', 'user_extend');

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

    /**
     * [user description]
     *
     * @return  [type]  [return description]
     */
    public function user_extend()
    {
        return $this->belongsTo('App\Modules\Users\Models\UserExtend', 'user_id');
    }

}
