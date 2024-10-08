<?php

namespace App\Modules\Users\Sform\Traits;

/**
 * Undocumented trait
 */
trait SformCheckTrait
{

    /**
     * Undocumented function
     *
     * @param [type] $var
     * @return void
     */
    public function form_check($key)
    {
        if ($key) {
            $checked = true;
        } else {
            $checked = false;
        }

        return $checked;
    }

    /**
     * Undocumented function
     *
     * @param [type] $var
     * @return void
     */
    public function form_check_value($key, $value)
    {
        if ($key == $value) {
            $checked = true;
        } else {
            $checked = false;
        }

        return $checked;
    }

}
