<?php

namespace Modules\Pollbooth\Contracts;


interface PollboothInterface {

     /**
     * [PollNewest description]
     *
     * @param   int   $id  [$id description]
     *
     * @return  void       [return description]
     */
    public function PollNewest(int $id = null);

    /**
     * [pollSecur description]
     *
     * @param   [type]  $pollID  [$pollID description]
     *
     * @return  [type]           [return description]
     */
    public function pollSecur($pollID);   

}
