<?php


namespace Npds\Database\Query\Adapter;

use Npds\Database\Query\Adapter as BaseAdapter;


class MySQL extends BaseAdapter
{
    
    /**
     * [$sanitizer description]
     *
     * @var [type]
     */
    protected $sanitizer = '`';
}
