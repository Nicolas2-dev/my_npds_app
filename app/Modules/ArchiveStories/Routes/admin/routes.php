<?php

use Npds\Routing\Router;


//
Router::get('admin/archive', 'App\Modules\ArchiveStories\Controllers\Admin\AdminArchiveStories@configureArchive');

Router::post('admin/archive/save', 'App\Modules\ArchiveStories\Controllers\Admin\AdminArchiveStories@SaveSetArchive_stories');


