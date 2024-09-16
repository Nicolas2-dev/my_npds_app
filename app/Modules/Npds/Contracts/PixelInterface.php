<?php

namespace App\Modules\Npds\Contracts;


interface PixelInterface {

    /**
     * [dataimagetofileurl description]
     *
     * @param   [type]  $base_64_string  [$base_64_string description]
     * @param   [type]  $output_path     [$output_path description]
     *
     * @return  [type]                   [return description]
     */
    public function dataimagetofileurl($base_64_string, $output_path);

}
