<?php

namespace Modules\Upload\Controllers\Admin;

use Modules\Npds\Core\AdminController;


class Upload extends AdminController
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
    protected $hlpfile = 'upload';

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
    protected $f_meta_nom = 'upConfigure';


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
        $this->f_titre = __d('upload', 'Configuration Upload');

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
     * @param [type] $xmax_size
     * @param [type] $xdocumentroot
     * @param [type] $xautorise_upload_p
     * @param [type] $xracine
     * @param [type] $xrep_upload
     * @param [type] $xrep_cache
     * @param [type] $xrep_log
     * @param [type] $xurl_upload
     * @param [type] $xurl_upload_css
     * @param [type] $xed_profil
     * @param [type] $xed_nb_images
     * @param [type] $xextension_autorise
     * @param [type] $xwidth_max
     * @param [type] $xheight_max
     * @param [type] $xquota
     * @return void
     */
    public function uploadSave($xmax_size, $xdocumentroot, $xautorise_upload_p, $xracine, $xrep_upload, $xrep_cache, $xrep_log, $xurl_upload, $xurl_upload_css, $xed_profil, $xed_nb_images, $xextension_autorise, $xwidth_max, $xheight_max, $xquota)
    {
        $file = file("modules/upload/upload.conf.php");
        $file[16] = "\$max_size = $xmax_size;\n";
        $file[21] = "\$DOCUMENTROOT = '$xdocumentroot';\n";
        $file[24] = "\$autorise_upload_p = '$xautorise_upload_p';\n";
        $file[28] = "\$racine = '$xracine';\n";
        $file[31] = "\$rep_upload = \$racine.'$xrep_upload';\n";
        $file[34] = "\$rep_cache = \$racine.'$xrep_cache';\n";
        $file[37] = "\$rep_log = \$racine.'$xrep_log';\n";
        $file[40] = "\$url_upload = '$xurl_upload';\n";
        $file[57] = "\$url_upload_css = '$xurl_upload_css';\n";
        $profil = array('0', '0', '0', '0');

        if ($xed_profil) {
            foreach ($profil as $k => $v) {
                if (in_array($k, $xed_profil)) {
                    $profil[$k] = 1;
                }
            }
        }

        $xed_profil = str_replace('|', '', implode('|', $profil));

        $file[67] = "\$ed_profil = '$xed_profil';\n";
        $file[70] = "\$ed_nb_images = $xed_nb_images;\n";

        $xextension_autorise = implode(' ', $xextension_autorise);

        $file[73] = "\$extension_autorise = '$xextension_autorise';\n";
        $file[76] = "\$width_max = $xwidth_max;\n";
        $file[77] = "\$height_max = $xheight_max;\n";
        $file[80] = "\$quota = $xquota;\n";

        $fic = fopen("modules/upload/upload.conf.php", "w");
        foreach ($file as $n => $ligne) {
            fwrite($fic, $ligne);
        }

        fclose($fic);
    }

}
