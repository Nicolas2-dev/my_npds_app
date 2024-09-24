<?php

namespace Npds\Themes;

use Npds\Config\Config;

/**
 * Undocumented class
 */
class Manager
{
    
    /**
     * [bootstrap description]
     *
     * @return  [type]  [return description]
     */
    public static function bootstrap()
    {
        $themes = Config::get('themes');

        if (! $themes) {
            return;
        }

        foreach ($themes as $theme) {
            $filePath = str_replace('/', DS, APPPATH.'Themes/'.$theme.'/Bootstrap/bootstrap.php');

            if (!is_readable($filePath)) {
                continue;
            }

            require $filePath;

            //
            static::boot_config($theme);
        }
    }

    /**
     * [boot_config description]
     *
     * @param   [type]  $theme  [$module description]
     *
     * @return  [type]           [return description]
     */
    public static function boot_config($theme)
    {
        $filePath = str_replace('/', DS, APPPATH.'Themes/'.$theme.'/config');
        
        // Load the configuration files.
        foreach (glob($filePath.'/*.php') as $path) {
            $key = lcfirst(pathinfo($path, PATHINFO_FILENAME));
            Config::set(strtolower($theme).'.'.$key, require($path));
        }
    }

}
