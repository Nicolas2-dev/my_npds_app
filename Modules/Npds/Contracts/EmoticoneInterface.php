<?php

namespace Modules\Npds\Contracts;


interface EmoticoneInterface {

    /**
     * [smilie description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    public function smilie($message);

    /**
     * [smile description]
     *
     * @param   [type]  $message  [$message description]
     *
     * @return  [type]            [return description]
     */
    public function smile($message);
    
    /**
     * [putitems_more description]
     *
     * @return  [type]  [return description]
     */
    public function putitems_more();
    
    /**
     * [putitems description]
     *
     * @param   [type]  $targetarea  [$targetarea description]
     *
     * @return  [type]               [return description]
     */
    public function putitems($targetarea);    

}
