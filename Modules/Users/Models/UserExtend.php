<?php

namespace Modules\Users\Models;

use Npds\ORM\Model as BaseModel;

/**
 * [UserStatus description]
 */
class UserExtend extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'users_extend';

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
    protected $relations = array('user', 'user_status');

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
    public function user_status()
    {
        return $this->belongsTo('Modules\Users\Models\UserStatus', 'uid');
    }

}
