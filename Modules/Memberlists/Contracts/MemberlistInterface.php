<?php

namespace Modules\Memberlists\Contracts;


interface MemberlistInterface {

    /**
     * Undocumented function
     *
     * @return void
     */
    public function alpha();

    /**
     * Undocumented function
     *
     * @param [type] $ibid
     * @return void
     */
    public function unique($ibid);

    /**
     * Undocumented function
     *
     * @param [type] $letter
     * @return void
     */
    public function SortLinks($letter);

    /**
     * Undocumented function
     *
     * @param [type] $user_avatar
     * @return void
     */
    public function avatar($user_avatar);

}
