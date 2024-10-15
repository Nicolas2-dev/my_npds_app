<?php

with(\Modules\News\Bootstrap\NewsKernel::getInstance(__FILE__))
    //
    ->aliases_loader()
    //
    ->load_constant()
    //
    ->load_helper()
    //
    ->load_boxe()
    //
    ->load_route_web()
    //
    ->load_route_admin()
    //
    ->load_route_api();
