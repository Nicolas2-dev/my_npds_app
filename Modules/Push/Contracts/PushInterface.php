<?php

namespace Modules\Push\Contracts;


interface PushInterface {

     /**
     * Undocumented function
     *
     * @return void
     */
    public function push_menu();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_news();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_poll();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_faq();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_members();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function push_links();

    /**
     * Undocumented function
     *
     * @param [type] $string
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    public function convert_nl($string, $from, $to);

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function links($ibid);

}
