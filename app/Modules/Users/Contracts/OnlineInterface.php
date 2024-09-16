<?php

namespace App\Modules\Users\Contracts;


interface OnlineInterface {

    /**
     * [Who_Online description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online();

    /**
     * [Who_Online_Sub description]
     *
     * @return  [type]  [return description]
     */
    public function Who_Online_Sub();
    
    /**
     * [Site_Load description]
     *
     * @return  [type]  [return description]
     */
    public function Site_Load();    

    /**
     * [online_members description]
     *
     * @return  [type]  [return description]
     */
    public function online_members();

}
