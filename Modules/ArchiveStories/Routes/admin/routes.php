<?php

use Npds\Routing\Router;


//
Router::get('admin/archive', 'Modules\ArchiveStories\Controllers\Admin\AdminArchiveStories@configureArchive');

Router::post('admin/archive/save', 'Modules\ArchiveStories\Controllers\Admin\AdminArchiveStories@SaveSetArchive_stories');


