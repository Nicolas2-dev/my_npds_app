<?php

namespace Modules\Users\Support\Traits;


/**
 * Undocumented trait
 */
trait UserMinisiteTrait
{

    /**
     * Note voir dans module minisite si la function existe  et non duppliquer !
     * Undocumented function
     *
     * @param [type] $chng_mns
     * @param [type] $chng_uname
     * @return void
     */
    public function Minisites($chng_mns, $chng_uname)
    {
        // Création de la structure pour les MiniSites dans storage/users_private/$chng_uname
        if ($chng_mns) {
            //include("modules/upload/upload.conf.php");
    
            if ($DOCUMENTROOT == '') {
                global $DOCUMENT_ROOT;
                $DOCUMENTROOT = ($DOCUMENT_ROOT) ? $DOCUMENT_ROOT : $_SERVER['DOCUMENT_ROOT'];
            }
    
            $user_dir = $DOCUMENTROOT . $racine . "/storage/users_private/" . $chng_uname;
            $repertoire = $user_dir . "/mns";
    
            if (!is_dir($user_dir)) {
                @umask("0000");
    
                if (@mkdir($user_dir, 0777)) {
                    $fp = fopen($user_dir . '/index.html', 'w');
                    fclose($fp);
                    @umask("0000");
    
                    if (@mkdir($repertoire, 0777)) {
                        $fp = fopen($repertoire . '/index.html', 'w');
                        fclose($fp);
    
                        $fp = fopen($repertoire . '/.htaccess', 'w');
                        @fputs($fp, 'Deny from All');
                        fclose($fp);
                    }
                }
            } else {
                @umask("0000");
    
                if (@mkdir($repertoire, 0777)) {
                    $fp = fopen($repertoire . '/index.html', 'w');
                    fclose($fp);
    
                    $fp = fopen($repertoire . '/.htaccess', 'w');
                    @fputs($fp, "Deny from All");
                    fclose($fp);
                }
            }
    
            // copie de la matrice par défaut
            $directory = $racine . '/modules/blog/matrice';
            $handle = opendir($DOCUMENTROOT . $directory);
    
            while (false !== ($file = readdir($handle))) {
                $filelist[] = $file;
            }
    
            asort($filelist);
    
            foreach ($filelist as $key => $file) {
                if ($file <> '.' and $file <> '..') {
                    @copy($DOCUMENTROOT . $directory . '/' . $file, $repertoire . '/' . $file);
                }
            }
    
            closedir($handle);
    
            unset($filelist);
    
            global $aid;
            Ecr_Log('security', "CreateMiniSite($chng_uname) by AID : $aid", '');
        }
    }

}
