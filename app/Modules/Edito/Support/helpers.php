<?php

use App\Modules\Edito\Library\EditoManager;


if (! function_exists('aff_edito'))
{
    /**
     * [aff_edito description]
     *
     * @return  [type]  [return description]
     */
    function aff_edito()
    {
        return EditoManager::getInstance()->aff_edito();
    }
}

if (! function_exists('fab_edito'))
{
    /**
     * [fab_edito description]
     *
     * @return  [type]  [return description]
     */
    function fab_edito()
    {
        return EditoManager::getInstance()->fab_edito();
    }
}
