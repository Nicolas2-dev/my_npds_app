<?php

namespace Modules\Newsletter\Contracts;


interface NewsletterInterface {

     /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function SuserCheck($email);

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function error_handler($ibid);

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function error_handler_admin($ibid);

    /**
     * 
     * Undocumented function
     *
     * @return void
     */
    public function ShowHeader();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function ShowBody();

    /**
     * Undocumented function
     *
     * @return void
     */
    public function ShowFooter();

}
