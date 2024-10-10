<?php

namespace Modules\Npds\Contracts;


interface PaginatorInterface {

    /**
     * [paginate_single description]
     *
     * @param   [type]  $url              [$url description]
     * @param   [type]  $urlmore          [$urlmore description]
     * @param   [type]  $total            [$total description]
     * @param   [type]  $current          [$current description]
     * @param   [type]  $adj              [$adj description]
     * @param   [type]  $topics_per_page  [$topics_per_page description]
     * @param   [type]  $start            [$start description]
     *
     * @return  [type]                    [return description]
     */
    public function paginate_single($url, $urlmore, $total, $current, $adj, $topics_per_page, $start);   

    /**
     * [paginate description]
     *
     * @param   [type]  $url              [$url description]
     * @param   [type]  $urlmore          [$urlmore description]
     * @param   [type]  $total            [$total description]
     * @param   [type]  $current          [$current description]
     * @param   [type]  $adj              [$adj description]
     * @param   [type]  $topics_per_page  [$topics_per_page description]
     * @param   [type]  $start            [$start description]
     *
     * @return  [type]                    [return description]
     */
    public function paginate($url, $urlmore, $total, $current, $adj, $topics_per_page, $start);

}
