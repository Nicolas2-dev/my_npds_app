<?php

use Npds\view\View;
use Modules\Npds\Support\Facades\Css;
use Modules\Theme\Support\Facades\Theme;


/**
 * Bloc Little News-Letter
 * 
 * syntaxe : function#lnlbox
 *
 * @return  [type]  [return description]
 */
function lnlbox()
{
    global $block_title;

    Theme::themesidebox(($block_title == '' ? __d('newsletter', 'La lettre') : $block_title), 
        View::make('Modules/Newsletter/Views/Boxe/Lnl_Boxe', 
            ['adminfoot' => Css::adminfoot('fv', '', 'var formulid = ["lnlblock"]', '0')
        ])
    );
}
