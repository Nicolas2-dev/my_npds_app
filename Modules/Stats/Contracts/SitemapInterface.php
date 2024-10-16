<?php

namespace Modules\Stats\Contracts;


interface SitemapInterface {

    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemapforum($prio);

    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemaparticle($prio);

    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemaprub($prio);

    /**
     * Undocumented function
     *
     * @param [type] $prio
     * @return void
     */
    public function sitemapdown($prio);

    /**
     * Undocumented function
     *
     * @param [type] $PAGES
     * @return void
     */
    public function sitemapothers($PAGES);

}
