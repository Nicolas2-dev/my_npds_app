<?php

/**
 * Test 
 */
with(\Modules\News\Bootstrap\NewsKernel::getInstance(dirname(__FILE__) . DS))
    //
    ->load_my_conf();
