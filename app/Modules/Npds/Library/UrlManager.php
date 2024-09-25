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
     * [redirect description]
     *
     * @param   [type]  $urlx  [$urlx description]
     *
     * @return  [type]         [return description]
     */
    public function redirect($urlx)
    {
        echo "<script type=\"text/javascript\">\n";
        echo "//<![CDATA[\n";
        echo "document.location.href='" . $urlx . "';\n";
        echo "//]]>\n";
        echo "</script>";
    }

    /**
     * Undocumented function
     *
     * @param [type] $urlx
     * @param [type] $time
     * @return void
     */
    public function redirect_time($urlx, $time)
    {
        echo "<script type=\"text/javascript\">\n";
        echo "//<![CDATA[\n";
        echo "setTimeout(function(){
            document.location.href='" . $urlx . "'
        } , ".$time.");";
        echo "//]]>\n";
        echo "</script>";
    }

}