<?php

namespace Modules\Authors\Models;

use Npds\Http\Request;
use Npds\ORM\Model as BaseModel;


/**
 * [User description]
 */
class Droit extends BaseModel
{
    /**
     * [$table description]
     *
     * @var [type]
     */
    protected $table = 'droits';

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

    /**
     * [deletedroits description]
     *
     * @param   [type]  $del_dr_aid  [$del_dr_aid description]
     *
     * @return  [type]               [return description]
     */
    function delete_droits($del_dr_aid)
    {
        with(new Droit())->where('d_aut_aid', $del_dr_aid)->delete();
    }
    
    /**
     * [updatedroits description]
     *
     * @param   [type]  $chng_aid  [$chng_aid description]
     *
     * @return  [type]             [return description]
     */
    function update_droits($chng_aid)
    {
        foreach (Request::post() as $y => $w) {
            if (stristr($y, 'ad_d_')) {
                with(new Droit())->insert(
                    [
                        'd_aut_aid' => $chng_aid,
                        'd_fon_fid' => $w,
                        'd_droits'  => 11111,
                    ]
                );
            }
        }
    }

}
