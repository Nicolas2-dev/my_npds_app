<?php

namespace Modules\News\Contracts;


interface NewsTopicInterface {


    /**
     * [getTopics description]
     *
     * @param   [type]  $s_sid  [$s_sid description]
     *
     * @return  [type]          [return description]
     */
    public function getTopics($s_sid);   
    
}
