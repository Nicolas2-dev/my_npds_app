<?php

namespace Modules\Twitter\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class Twitter extends AdminController
{
    
    /**
     * [$pdst description]
     *
     * @var [type]
     */
    protected $pdst = 0;

    /**
     * [$hlpfile description]
     *
     * @var [type]
     */
    protected $hlpfile = 'admtwi';

    /**
     * [$short_menu_admin description]
     *
     * @var bool
     */
    protected $short_menu_admin = true;

    /**
     * [$adminhead description]
     *
     * @var [type]
     */
    protected $adminhead = true;

    /**
     * [$f_meta_nom description]
     *
     * @var [type]
     */
    protected $f_meta_nom = 'npds_twi';


    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [before description]
     *
     * @return  [type]  [return description]
     */
    protected function before()
    {
        $this->f_titre = __d('twitter', 'npds_twitter');

        // Leave to parent's method the Flight decisions.
        return parent::before();
    }

    /**
     * [after description]
     *
     * @param   [type]  $result  [$result description]
     *
     * @return  [type]           [return description]
     */
    protected function after($result)
    {
        // Do some processing there, even deciding to stop the Flight, if case.

        // Leave to parent's method the Flight decisions.
        return parent::after($result);
    }

    /**
     * Undocumented function
     *
     * @param [type] $npds_twi_arti
     * @param [type] $npds_twi_urshort
     * @param [type] $npds_twi_post
     * @param [type] $consumer_key
     * @param [type] $consumer_secret
     * @param [type] $oauth_token
     * @param [type] $oauth_token_secret
     * @param [type] $tbox_width
     * @param [type] $tbox_height
     * @param [type] $class_sty_1
     * @param [type] $class_sty_2
     * @param [type] $ModPath
     * @param [type] $ModStart
     * @return void
     */
    public function SaveSettwi($npds_twi_arti, $npds_twi_urshort, $npds_twi_post, $consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret, $tbox_width, $tbox_height, $class_sty_1, $class_sty_2, $ModPath, $ModStart)
    {
        // $file_conf = fopen("modules/$ModPath/twi_conf.php", "w+");

        $content = "<?php \n";
        $content .= "/************************************************************************/\n";
        $content .= "/* DUNE by App                                                          */\n";
        $content .= "/* ===========================                                          */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* App Copyright (c) 2002-" . date('Y') . " by Philippe Brunier         */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
        $content .= "/* it under the terms of the GNU General Public License as published by */\n";
        $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
        $content .= "/*                                                                      */\n";
        $content .= "/* module App_twi version v.1.0                                         */\n";
        $content .= "/* twi_conf.php file 2015 by Jean Pierre Barbary (jpb)                  */\n";
        $content .= "/* dev team :                                                           */\n";
        $content .= "/************************************************************************/\n";

        if (!$npds_twi_arti) {
            $npds_twi_arti = 0;
        }

        $content .= "\$npds_twi_arti = $npds_twi_arti; // activation publication auto des news sur twitter\n";

        if (!$npds_twi_post) {
            $npds_twi_post = 0;
        }

        $content .= "\$npds_twi_post = $npds_twi_post; // activation publication auto des posts sur twitter\n";

        if (!$npds_twi_urshort) {
            $npds_twi_urshort = 0;
        }

        $content .= "\$npds_twi_urshort = $npds_twi_urshort; // activation du raccourciceur d'url\n";
        $content .= "\$consumer_key = \"$consumer_key\"; //\n";
        $content .= "\$consumer_secret = \"$consumer_secret\"; //\n";
        $content .= "\$oauth_token = \"$oauth_token\"; //\n";
        $content .= "\$oauth_token_secret = \"$oauth_token_secret\"; //\n";
        $content .= "// interface bloc \n";
        $content .= "\$tbox_width = \"$tbox_width\"; // largeur de la tweet box\n";
        $content .= "\$tbox_height = \"$tbox_height\"; // hauteur de la tweet box\n";
        $content .= "// style \n";
        $content .= "\$class_sty_1 = \"$class_sty_1\"; // titre de la page\n";
        $content .= "\$class_sty_2 = \"$class_sty_2\"; // sous-titre de la page\n";
        $content .= "\$npds_twi_versus = \"v.1.0\";\n";
        $content .= "?>";

        fwrite($file_conf, $content);
        fclose($file_conf);

        $file_controleur = '';

        // if (file_exists('modules/'.$ModPath.'/twi_conf.php')) {
        //     include ('modules/'.$ModPath.'/twi_conf.php');
        // }

        if ($npds_twi_urshort <> 1) {
            $file_controleur = fopen("s.php", "w+");

            $content = "<?php \n";
            $content .= "/************************************************************************/\n";
            $content .= "/* DUNE by App                                                          */\n";
            $content .= "/* ===========================                                          */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* App Copyright (c) 2002-" . date('Y') . " by Philippe Brunier         */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
            $content .= "/* it under the terms of the GNU General Public License as published by */\n";
            $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* module App_twi version v.1.0                                         */\n";
            $content .= "/* a.php file 2015 by Jean Pierre Barbary (jpb)                         */\n";
            $content .= "/* dev team :                                                           */\n";
            $content .= "/************************************************************************/\n";
            $content .= "\n";
            $content .= "\$fol=preg_replace ('#s\.php/(\d+)\$#','',\$_SERVER['PHP_SELF']);\n";
            $content .= "preg_match ('#/s\.php/(\d+)\$#', \$_SERVER['PHP_SELF'],\$res);\n";
            $content .= "header('Location: http://'.\$_SERVER['HTTP_HOST'].\$fol.'article.php?sid='.\$res[1]);\n";
            $content .= "?>";

            fwrite($file_controleur, $content);
            fclose($file_controleur);

            $file_controleur = fopen("s", "w+");

            $content = "<?php \n";
            $content .= "/************************************************************************/\n";
            $content .= "/* DUNE by App                                                          */\n";
            $content .= "/* ===========================                                          */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* App Copyright (c) 2002-" . date('Y') . " by Philippe Brunier         */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* This program is free software. You can redistribute it and/or modify */\n";
            $content .= "/* it under the terms of the GNU General Public License as published by */\n";
            $content .= "/* the Free Software Foundation; either version 3 of the License.       */\n";
            $content .= "/*                                                                      */\n";
            $content .= "/* module App_twi version v.1.0                                         */\n";
            $content .= "/* a file 2015 by Jean Pierre Barbary (jpb)                             */\n";
            $content .= "/* dev team :                                                           */\n";
            $content .= "/************************************************************************/\n";
            $content .= "\n";
            $content .= "\$fol=preg_replace ('#s/(\d+)\$#','',\$_SERVER['PHP_SELF']);\n";
            $content .= "preg_match ('#/s/(\d+)\$#', \$_SERVER['PHP_SELF'],\$res);\n";
            $content .= "header('Location: http://'.\$_SERVER['HTTP_HOST'].\$fol.'article.php?sid='.\$res[1]);\n";
            $content .= "?>";

            fwrite($file_controleur, $content);
            fclose($file_controleur);
        }

    }

}
