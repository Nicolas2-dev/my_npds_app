<?php

namespace Modules\Users\Models;

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
     * Undocumented variable
     *
     * @var string
     */
    protected $primaryKey = 'uid';

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
        return $this->belongsTo('Modules\Users\Models\User', 'uid');
    }

    /**
     * [user description]
     *
     * @return  [type]  [return description]
     */
    public function user_extend()
    {
        return $this->belongsTo('Modules\Users\Models\UserExtend', 'uid');
    }

}
