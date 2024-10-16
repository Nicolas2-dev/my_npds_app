<?php

namespace Modules\Theme\Support\Traits;


trait ThemeOnloadBodyTrait
{

    /**
     * [Body_Onload_defaul description]
     *
     * @return  [type]  [return description]
     */
    public function Body_Onload_defaul()
    {
        if (file_exists(module_path("Theme/Support/Include/body_onload.php"))) {
            echo $this->script_start();
            include(module_path("Theme/Support/Include/body_onload.php"));
            echo $this->script_end();
        }
    }

    /**
     * [Body_Onload_theme description]
     *
     * @return  [type]  [return description]
     */
    public function Body_Onload_theme()
    {
        if (file_exists(theme_path($this->theme."/Support/Include/body_onload.php"))) {
            echo $this->script_start();
            include(theme_path($this->theme."/Support/Include/body_onload.php"));
            echo $this->script_end();
        }
    }

    /**
     * [script_start description]
     *
     * @return  [type]  [return description]
     */
    private function script_start()
    {
        return '
        <script type="text/javascript">
            //<![CDATA[
                function init() {';
    }

    /**
     * [script_end description]
     *
     * @return  [type]  [return description]
     */
    private function script_end()
    {
        return '
                }
            //]]>
        </script>';
    }

}
