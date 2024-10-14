<?php

namespace Modules\News\Bootstrap;

use Npds\Foundation\AliasLoader;

/**
 * Undocumented class
 */
class NewsKernel
{

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected static $aliases = [
        // Modules News
        'NewsAutomated'     => 'Modules\News\Support\Facades\NewsAutomated',
        'News'              => 'Modules\News\Support\Facades\News',
        'NewsPublication'   => 'Modules\News\Support\Facades\NewsPublication',
        'NewsTopic'         => 'Modules\News\Support\Facades\NewsTopic',
        'NewsUltramode'     => 'Modules\News\Support\Facades\NewsUltramode',
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
