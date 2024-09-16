<?php

namespace App\Modules\Download\Contracts;


interface DownloadInterface {

    /**
     * [topdownload_data description]
     *
     * @param   [type]  $form   [$form description]
     * @param   [type]  $ordre  [$ordre description]
     *
     * @return  [type]          [return description]
     */
    public function topdownload_data($form, $ordre);

}
