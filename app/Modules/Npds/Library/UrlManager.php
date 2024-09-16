<?php

namespace App\Modules\Npds\Library;

use App\Modules\Npds\Contracts\UrlInterface;


class UrlManager implements UrlInterface 
{

    /**
     * [$instance description]
     *
     * @var [type]
     */
    protected static $instance;


    /**
     * [getInstance description]
     *
     * @return  [type]  [return description]
     */
    public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    /**
     * [redirect_url description]
     *
     * @param   [type]  $urlx  [$urlx description]
     *
     * @return  [type]         [return description]
     */
    public function redirect_url($urlx)
    {
        echo "<script type=\"text/javascript\">\n";
        echo "//<![CDATA[\n";
        echo "document.location.href='" . $urlx . "';\n";
        echo "//]]>\n";
        echo "</script>";
    }

}