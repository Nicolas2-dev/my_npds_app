<?php

use App\Modules\Blocks\Library\BlockManager;

if (! function_exists('leftblocks'))
{
    /**
     * [leftblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    function leftblocks($moreclass)
    {
        return BlockManager::getInstance()->leftblocks($moreclass);
    }
}

if (! function_exists('rightblocks'))
{
    /**
     * [rightblocks description]
     *
     * @param   [type]  $moreclass  [$moreclass description]
     *
     * @return  [type]              [return description]
     */
    function rightblocks($moreclass)
    {
        return BlockManager::getInstance()->rightblocks($moreclass);
    }
}
