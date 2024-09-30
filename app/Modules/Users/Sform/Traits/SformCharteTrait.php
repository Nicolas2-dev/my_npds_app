<?php

namespace App\Modules\Users\Sform\Traits;

use Npds\Http\Request;

/**
 * Undocumented trait
 */
trait SformCharteTrait
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function charte()
    {
        // --- CHARTE du SITE
        $this->sform->add_checkbox(
            'charte', 
            '<a href="'. site_url('static.php?op=charte.html') .'" target="_blank">' . __d('users', 'Vous devez accepter la charte d\'utilisation du site') . '</a>', 
            "1", 
            true, 
            false
        );
        // --- CHARTE du SITE
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function charte_confirme()
    {
        // --- CHARTE du SITE
        if (Request::post('charte', 0) == 0) {

            $this->sform->add_extra(
                '<div class="alert alert-danger lead mt-3">
                    <i class="fa fa-exclamation me-2"></i>' . __d('users', 'Vous devez accepter la charte d\'utilisation du site') . '
                </div>'
            );                
        }
        // --- CHARTE du SITE
    }


}
