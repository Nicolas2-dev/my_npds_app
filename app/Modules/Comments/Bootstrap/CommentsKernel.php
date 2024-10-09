<?php

namespace App\Modules\Comments\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class CommentsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Module Comments
        'Comment'         => 'App\Modules\Comments\Support\Facades\Comment',
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function aliases_loader()
    {
        AliasLoader::getInstance(static::$aliases)->register();
    }

}
