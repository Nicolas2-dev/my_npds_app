<?php

namespace Modules\Npds\Models;

use Npds\ORM\Model as BaseModel;


/**
 * [User description]
 */
class Counter extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'counter';

    /**
     * [$primaryKey description]
     *
     * @var [type]
     */
    protected $primaryKey = 'id_stat';

    /**
     * [$fields description]
     *
     * @var [type]
     */
    protected $fields = array('type', 'var', 'count');

    /**
     * [$relations description]
     *
     * @var [type]
     */
    protected $relations = array();

    /**
     * [$timestamps description]
     *
     * @var [type]
     */
    protected $timestamps = false;

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
     * [stats description]
     *
     * @return  [type]  [return description]
     */
    public static function stats()
    {
        return with(new Counter())->select('count')->where('type', 'total')->first()['count'];
    }

}
