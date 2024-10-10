<?php

namespace Tests;

use Modules\Theme\Support\Facades\Theme;

test('the image row', function () {
    
    include 'public/index.php';


    $string = Theme::theme_image_row('stats/explorer.gif', 'stats');
  
    expect($string)->ToBeString();
 });

