<?php

namespace Modules\Module\Support\Traits;


/**
 * Undocumented trait
 */
trait ModuleInstallCopyrightTrait
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function nmig_copyright()
    {
        global $ModInstall, $ModDesinstall;
    
        $clspin = ' text-success';
    
        if ($ModInstall == '' && $ModDesinstall != '') {
            $clspin = ' text-danger';
        }
        
        $display = '
            <hr class="mt-4" />
            <div class="d-flex align-items-center">
                <div role="status" class="small">Installation by App Module Installer v2.0</div>
                <div class="spinner-border ms-auto ' . $clspin . '" aria-hidden="true"  style="width: 1.5rem; height: 1.5rem;"></div>
            </div>';
    
        return $display;
    }

}
