<?php

namespace Npds\Foundation;

use Npds\Config\Config;
use RuntimeException;

/**
 * Undocumented class
 */
class AliasLoader
{

    /**
     * [initialize description]
     *
     * @return  [type]  [return description]
     */
    public static function initialize()
    {
        $classes = Config::get('app.aliases', array());

        foreach ($classes as $classAlias => $className) {
            // This ensures the alias is created in the global namespace.
            $classAlias = '\\' .ltrim($classAlias, '\\');

            // Check if the Class already exists.
            if (class_exists($classAlias)) {
                // Bail out, a Class already exists with the same name.
                throw new RuntimeException('A class [' .$classAlias .'] already exists with the same name.');
            }

            class_alias($className, $classAlias);
        }
    }
    
}
