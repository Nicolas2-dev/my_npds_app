<?php

namespace App\Modules\Users\Models;

use Npds\ORM\Model as BaseModel;


/**
 * [User description]
 */
class User extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'users';

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
    protected $relations = array('user_status', 'user_extend', 'priv_msg');

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
     * [user_status description]
     *
     * @return  [type]  [return description]
     */
    public function user_status()
    {
        return $this->hasMany('App\Modules\Users\Models\UserStatus', 'uid');
    }

    /**
     * [user_status description]
     *
     * @return  [type]  [return description]
     */
    public function user_extend()
    {
        return $this->hasMany('App\Modules\Users\Models\UserExtend', 'uid');
    }

    /**
     * [user_status description]
     *
     * @return  [type]  [return description]
     */
    public function priv_msg()
    {
        return $this->hasMany('App\Modules\Messenger\Models\PrivMsg', 'uid');
    }

    /**
     * [stats description]
     *
     * @return  [type]  [return description]
     */
    public static function stats()
    {
        return with(new User())->where('uname', '!=', 'Anonyme')->count();
    }

}
