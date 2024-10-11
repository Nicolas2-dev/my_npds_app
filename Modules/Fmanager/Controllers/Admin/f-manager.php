<?php

use Npds\Routing\Url;
use Npds\Config\Config;
use Modules\Npds\Support\Facades\Auth;
use Modules\Fmanager\Library\Navigator;
use Modules\Npds\Support\Facades\Crypt;
use Modules\Npds\Support\Facades\Cookie;
use Modules\Theme\Support\Facades\Theme;
use Modules\Module\Support\Facades\Module;
use Modules\Npds\Support\Facades\Language;
use Modules\Groupes\Support\Facades\Groupe;
use Shared\Editeur\Support\Facades\Editeur;
use Modules\Fmanager\Support\Facades\Fmanager;

// Lancement sur un Répertoire en fonction d'un fichier de conf particulier
if ($FmaRep) {
    if (Module::filtre_module($FmaRep)) {

        $user = Auth::guard('user');

        // Si je ne trouve pas de fichier - est-ce que l'utilisateur fait partie d'un groupe ?
        if (!file_exists("modules/$ModPath/users/" . strtolower($FmaRep) . ".conf.php")) {

            if ($tab_groupe = Groupe::valid_group($user)) {

                // si j'ai au moins un groupe est ce que celui-ci dispose d'un fichier de configuration ?  - je m'arrête au premier groupe !
                foreach ($tab_groupe as $gp) {
                    $groupename = Q_select("SELECT groupe_name FROM groupes WHERE groupe_id='$gp' ORDER BY `groupe_id` ASC", 3600);

                    if (file_exists("modules/$ModPath/users/" . $groupename[0]['groupe_name'] . ".conf.php")) {
                        $FmaRep = $groupename[0]['groupe_name'];
                        break;
                    }
                }
            }
        }

        if (file_exists("modules/$ModPath/users/" . strtolower($FmaRep) . ".conf.php")) {

            $user = Auth::guard('user');

            // Est ce que je doit récupérer le theme si un utilisateur est connecté ?
            if (isset($user)) {

                $themelist  = Theme::list();
                $themelist  = explode(' ', $themelist);

                $pos = array_search(Cookie::cookie_user(9), $themelist);

                if ($pos !== false) {
                    Config::set('npds.Default_Theme', $themelist[$pos]);
                }
                
            }

            include("modules/$ModPath/users/" . strtolower($FmaRep) . ".conf.php");

            if (Fmanager::fma_autorise('a', '')) {
                $theme_fma      = $themeG_fma;
                $fic_minuscptr  = 0;
                $dir_minuscptr  = 0;
            } else {
                Access_Error();
            }
        } else {
            Access_Error();
        }
    } else {
        Access_Error();
    }
} else {
    Access_Error();
}

if (isset($browse)) {
    $ibid = rawurldecode(Crypt::decrypt($browse));

    if (substr(@php_uname(), 0, 7) == 'Windows') {
        $ibid = preg_replace('#[\*\?"<>|]#i', '', $ibid);
    } else {
        $ibid = preg_replace('#[\:\*\?"<>|]#i', '', $ibid);
    }

    $ibid = str_replace('..', '', $ibid);

    // contraint à rester dans la zone de repertoire définie (CHROOT)
    $base = $basedir_fma . substr($ibid, strlen($basedir_fma));
} else {
    //$browse = '';
    $base   = $basedir_fma;
}

// initialisation de la classe
$obj = new Navigator();
$obj->Extension = explode(' ', $extension_fma);

// traitements 
if (substr(@php_uname(), 0, 7) == "Windows") {
    $log_dir = str_replace($basedir_fma, '', $base);
} else {
    $log_dir = str_replace("\\", "/", str_replace($basedir_fma, '', $base));
}

include_once("modules/upload/upload.conf.php");

settype($op, 'string');

switch ($op) {

    case 'upload':
        if ($ficcmd_fma[0]) {
            if ($userfile != 'none') {

                include_once("modules/upload/lang/upload.lang-Config::get('npds.language').php");
                include_once("modules/upload/clsUpload.php");

                $upload = new Upload();
                $filename = trim($upload->getFileName("userfile"));

                if ($filename) {
                    $upload->maxupload_size = $max_size;
                    $auto = fma_filter('f', $filename, $obj->Extension);

                    if ($auto[0]) {
                        if (!$upload->saveAs($auto[2], $base . '/', 'userfile', true))
                            $Err = $upload->errors;
                        else
                            Ecr_Log('security', 'Upload File', $log_dir . '/' . $filename . ' IP=>' . getip());
                    } else
                        $Err = $auto[1];
                }
            }
        }
        break;

        // Répertoires
    case 'createdir':
        if ($dircmd_fma[0]) {
            $auto = fma_filter('d', $userdir, $obj->Extension);

            if ($auto[0]) {
                if (!$obj->Create('d', $base . '/' . $auto[2]))
                    $Err = $obj->Errors;
                else {
                    Ecr_Log('security', 'Create Directory', $log_dir . '/' . $userdir . ' IP=>' . getip());
                    $fp = fopen($base . '/' . $auto[2] . '/.htaccess', 'w');
                    fputs($fp, 'Deny from All');
                    fclose($fp);
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'renamedir':
        if ($dircmd_fma[1]) {
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-folder fs-1 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Renommer un répertoire');
                    $rename_dir = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="renamedir-save" />
                        <div class="mb-3">
                            <label><code> ' . extend_ascii($auto[2]) . '</code></label>
                            <input class="form-control" type="text" name="renamefile" value="' . extend_ascii($auto[2]) . '" />
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'renamedir-save':
        if ($dircmd_fma[1]) {
            // origine
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                // destination
                $autoD = fma_filter('d', $renamefile, $obj->Extension);

                if ($autoD[0]) {
                    $auto[3] = decrypt($browse);

                    if (!$obj->Rename($auto[3] . '/' . $auto[2], $auto[3] . '/' . $autoD[2]))
                        $Err = $obj->Errors;
                    else
                        Ecr_Log('security', 'Rename Directory', $log_dir . '/' . $autoD[2] . ' IP=>' . getip());
                } else
                    $Err = $autoD[1];
            } else
                $Err = $auto[1];
        }
        break;

    case 'removedir':
        if ($dircmd_fma[2]) {
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-folder fs-1 me-2 text-danger align-middle"></i><span class="text-danger">' . __d('fmanager', 'Supprimer un répertoire') . '</span>';
                    $remove_dir = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="removedir-save" />
                        <div class="mb-3">
                            ' . __d('fmanager', 'Confirmez-vous la suppression de') . ' <code>' . extend_ascii($auto[2]) . '</code>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-danger" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'removedir-save':
        if ($dircmd_fma[2]) {
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);
                @unlink($auto[3] . '/' . $auto[2] . '/.htaccess');
                @unlink($auto[3] . '/' . $auto[2] . '/pic-manager.txt');

                if (!$obj->RemoveDir($auto[3] . '/' . $auto[2])) {
                    $Err = $obj->Errors;
                } else
                    Ecr_Log('security', 'Delete Directory', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }
        break;

    case 'chmoddir':
        if ($dircmd_fma[3]) {
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-folder fs-1 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Changer les droits d\'un répertoire');
                    $chmod_dir = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="chmoddir-save" />
                        <div class="mb-3">
                            <label class="form-label" for="chmoddir" ><code>' . extend_ascii($auto[2]) . '</code></label>
                            <select class="form-select" id="chmoddir" name="chmoddir">
                                ' . chmod_pres($obj->GetPerms($auto[3] . '/' . $auto[2]), 'chmoddir') . '
                        </div>
                        <div class="mb-3">
                            <input class="btn btn-primary" type="submit" name="ok" value="' . __d('fmanager', 'Ok') . '" />
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'chmoddir-save':
        if ($dircmd_fma[3]) {
            $auto = fma_filter('d', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {

                    settype($chmoddir, 'integer');

                    if (!$obj->ChgPerms($auto[3] . '/' . $auto[2], $chmoddir))
                        $Err = $obj->Errors;
                    else
                        Ecr_Log('security', 'Chmod Directory', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
                }
            } else
                $Err = $auto[1];
        }
        break;

        // Fichiers
    case 'createfile':
        if ($ficcmd_fma[0]) {
            $auto = fma_filter('f', $userfile, $obj->Extension);

            if ($auto[0]) {
                if (!$obj->Create('f', $base . '/' . $auto[2]))
                    $Err = $obj->Errors;
                else
                    Ecr_Log('security', 'Create File', $log_dir . '/' . $userfile . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }
        break;

    case 'renamefile':
        if ($ficcmd_fma[1]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-file-earmark fs-2 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Renommer un fichier');
                    $rename_file = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="renamefile-save" />
                        <div class="mb-3">
                            <label class="form-label" for="renamefile"><code>' . extend_ascii($auto[2]) . '</code></label>
                            <input class="form-control" type="text" size="60" id="renamefile" name="renamefile" value="' . extend_ascii($auto[2]) . '" />
                        </div>
                        <div class="mb-3">
                            <input class="btn btn-primary" type="submit" name="ok" value="' . __d('fmanager', 'Ok') . '" />
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'renamefile-save':
        if ($ficcmd_fma[1]) {
            // origine
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                // destination
                $autoD = fma_filter('f', $renamefile, $obj->Extension);

                if ($autoD[0]) {
                    $auto[3] = decrypt($browse);

                    if (!$obj->Rename($auto[3] . '/' . $auto[2], $auto[3] . '/' . $autoD[2]))
                        $Err = $obj->Errors;
                    else
                        Ecr_Log('security', 'Rename File', $log_dir . '/' . $autoD[2] . ' IP=>' . getip());
                } else
                    $Err = $autoD[1];
            } else
                $Err = $auto[1];
        }
        break;

    case 'movefile':
        if ($ficcmd_fma[1]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-file-earmark fs-2 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Déplacer / Copier un fichier');
                    $move_file = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <div class="mb-3">
                            <select class="form-select me-2" name="op">
                                <option value="movefile-save" selected="selected"> ' . __d('fmanager', 'Déplacer') . '</option>
                                <option value="copyfile-save">' . __d('fmanager', 'Copier') . '</option>
                            </select>
                            <code>' . extend_ascii($auto[2]) . '</code>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" name="movefile">';

                    $move_file .= '<option value="">/</option>';

                    $arb = explode('|', $obj->GetDirArbo($basedir_fma));
                    foreach ($arb as $rep) {
                        if ($rep != '') {
                            $rep2 = str_replace($basedir_fma, '', $rep);

                            if (fma_autorise('d', basename($rep)))
                                $move_file .= '<option value="' . $rep2 . '">' . str_replace('/', ' / ', $rep2) . '</option>';
                        }
                    }

                    $move_file .= '
                            </select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'movefile-save':
        if ($ficcmd_fma[1]) {

            // origine
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                // destination
                $auto[3] = decrypt($browse);

                if (!$obj->Move($auto[3] . '/' . $auto[2], $basedir_fma . $movefile . "/" . $auto[2]))
                    $Err = $obj->Errors;
                else
                    Ecr_Log('security', 'Move File', $log_dir . '/' . $auto[2] . ' TO ' . $movefile . '/' . $auto[2] . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }
        break;

    case 'copyfile-save':
        if ($ficcmd_fma[1]) {

            // origine
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                // destination
                $auto[3] = decrypt($browse);

                if (!$obj->Copy($auto[3] . '/' . $auto[2], $basedir_fma . $movefile . '/' . $auto[2]))
                    $Err = $obj->Errors;
                else
                    Ecr_Log('security', 'Copy File', $log_dir . '/' . $auto[2] . ' TO ' . $movefile . '/' . $auto[2] . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }
        break;

    case 'removefile':
        if ($ficcmd_fma[2]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists("$auto[3]/$auto[2]")) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-file-earmark fs-2 me-2 text-danger align-middle"></i><span class="text-danger">' . __d('fmanager', 'Supprimer un fichier') . '</span>';
                    $remove_file = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="removefile-save" />
                        <div class="mb-3 lead">
                            ' . __d('fmanager', 'Confirmez-vous la suppression de') . ' <code>' . extend_ascii($auto[2]) . '</code>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-danger" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'removefile-save':
        if ($ficcmd_fma[2]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (!$obj->Remove($auto[3] . '/' . $auto[2]))
                    $Err = $obj->Errors;
                else
                    Ecr_Log('security', 'Delete File', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }
        break;

    case 'chmodfile':
        if ($ficcmd_fma[3]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-file-earmark fs-2 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Changer les droits d\'un fichier') . '</span>';
                    $chmod_file = '
                    <form method="post" action="modules.php">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="chmodfile-save" />
                        <div class="mb-3">
                            <label class="form-label" for="chmodfile"><code>' . extend_ascii($auto[2]) . '</code></label>
                            <select class="form-select" id="chmodfile" name="chmodfile">
                                ' . chmod_pres($obj->GetPerms($auto[3] . '/' . $auto[2]), "chmodfile") . '
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                        </div>
                    </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'chmodfile-save':
        if ($ficcmd_fma[3]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {

                    settype($chmodfile, "integer");

                    if (!$obj->ChgPerms($auto[3] . '/' . $auto[2], $chmodfile))
                        $Err = $obj->Errors;
                    else
                        Ecr_Log('security', 'Chmod File', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
                }
            } else
                $Err = $auto[1];
        }

        $op = '';
        break;

    case 'editfile':
        if ($ficcmd_fma[4]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $auto[3] = decrypt($browse);

                if (file_exists($auto[3] . '/' . $auto[2])) {
                    $theme_fma = $themeC_fma;
                    $cmd = '<i class="bi bi-file-earmark fs-2 me-2 align-middle text-muted"></i>' . __d('fmanager', 'Editer un fichier') . '</span>';

                    $fp = fopen($auto[3] . '/' . $auto[2], 'r');

                    if (filesize($auto[3] . '/' . $auto[2]) > 0)
                        $Fcontent = fread($fp, filesize($auto[3] . '/' . $auto[2]));

                    fclose($fp);
                    $edit_file = '
                    <form method="post" action="modules.php" name="adminForm">
                        <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                        <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                        <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                        <input type="hidden" name="browse" value="' . $browse . '" />
                        <input type="hidden" name="att_name" value="' . $att_name . '" />
                        <input type="hidden" name="op" value="editfile-save" />
                        <div class="mb-3 row">
                            <label class="form-label col-12" for="editfile"><code>' . extend_ascii($auto[2]) . '</code></label>';

                    settype($Fcontent, 'string');

                    $edit_file .= '
                     <div class="col-12">
                        <textarea class="tin form-control" id="editfile" name="editfile" rows="18">' . htmlspecialchars($Fcontent, ENT_COMPAT | ENT_HTML401, cur_charset) . '</textarea>
                     </div>
                  </div>';

                    $tabW = explode(' ', $extension_Wysiwyg_fma);
                    $suffix = strtoLower(substr(strrchr($att_name, '.'), 1));

                    if (in_array($suffix, $tabW))
                        $edit_file .= aff_editeur('editfile', 'true');

                    $edit_file .= '
                  <button class="btn btn-primary" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
               </form>';
                }
            } else
                $Err = $auto[1];
        }
        break;

    case 'editfile-save':
        if ($ficcmd_fma[4]) {
            $auto = fma_filter('f', $att_name, $obj->Extension);

            if ($auto[0]) {
                $tabW = explode(' ', $extension_Edit_fma);
                $suffix = strtoLower(substr(strrchr($att_name, '.'), 1));

                if (in_array($suffix, $tabW)) {
                    $auto[3] = decrypt($browse);

                    if (file_exists($auto[3] . '/' . $auto[2])) {
                        $fp = fopen($auto[3] . '/' . $auto[2], 'w');
                        fputs($fp, stripslashes($editfile));
                        fclose($fp);

                        Ecr_Log('security', 'Edit File', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
                    }
                } else
                    Ecr_Log('security', 'Edit File forbidden', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
            } else
                $Err = $auto[1];
        }

        $op = '';
        break;

    case 'pict':
        $auto = fma_filter('d', $att_name, $obj->Extension);

        if ($auto[0]) {
            $auto[3] = decrypt($browse);

            if (file_exists($auto[3] . '/' . $auto[2])) {
                $theme_fma = $themeC_fma;
                $cmd = '<span class="text-muted"><i class="fa fa-image fa-2x me-2 align-middle"></i></span>' . __d('fmanager', 'Autoriser Pic-Manager') . ' >> ' . $auto[2];
                $pict_dir = '
                <form method="post" action="modules.php">
                <input type="hidden" name="ModPath" value="' . $ModPath . '" />
                <input type="hidden" name="ModStart" value="' . $ModStart . '" />
                <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
                <input type="hidden" name="browse" value="' . $browse . '" />
                <input type="hidden" name="att_name" value="' . $att_name . '" />
                <input type="hidden" name="op" value="pict-save" />
                <div class="mb-3">
                    <label class="form-label" for="maxthumb">' . __d('fmanager', 'Taille maximum (pixel) de l\'imagette') . '</label>';

                $fp = @file($auto[3] . '/' . $auto[2] . '/pic-manager.txt');

                // La première ligne du tableau est un commentaire
                settype($fp[1], 'integer');

                $Max_thumb = $fp[1];
                if ($Max_thumb == 0)
                    $Max_thumb = 150;

                settype($fp[2], 'integer');

                $refresh = $fp[2];
                if ($refresh == 0)
                    $refresh = 3600;

                $pict_dir .= '
                    <input class="form-control" type="number" id="maxthumb" name="maxthumb" size="4" value="' . $Max_thumb . '" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="refresh">' . __d('fmanager', 'Temps de cache (en seconde) des imagettes') . '</label> 
                    <input class="form-control" type="number" id="refresh" name="refresh" size="6" value="' . $refresh . '" />
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit" name="ok">' . __d('fmanager', 'Ok') . '</button>
                </div>
                </form>';
            }
        } else
            $Err = $auto[1];
        break;

    case 'pict-save':
        $auto = fma_filter('d', $att_name, $obj->Extension);

        if ($auto[0]) {
            $auto[3] = decrypt($browse);
            $fp = fopen($auto[3] . '/' . $auto[2] . '/pic-manager.txt', 'w');

            settype($maxthumb, 'integer');

            fputs($fp, "Enable and customize pic-manager / to remove pic-manager : just remove pic-manager.txt\n");
            fputs($fp, $maxthumb . "\n");
            fputs($fp, $refresh . "\n");
            fclose($fp);

            Ecr_Log('security', 'Pic-Manager', $log_dir . '/' . $auto[2] . ' IP=>' . getip());
        } else
            $Err = $auto[1];
        break;

    case 'searchfile':
        $resp = $obj->SearchFile($base, $filesearch);

        if ($resp) {
            $resp = explode('|', $resp);
            array_pop($resp);
            $cpt = 0;

            foreach ($resp as $fic_resp) {

                // on limite le retour au niveau immédiatement inférieur au rep courant
                $rep_niv1 = explode('/', str_replace($base, '', $fic_resp));

                if (count($rep_niv1) < 4) {
                    $dir_search = basename(dirname($fic_resp));
                    $fic_search = basename($fic_resp);

                    if (fma_autorise('d', $dir_search)) {
                        if (fma_autorise('f', $fic_search)) {
                            $tab_search[$cpt][0] = $dir_search;
                            $tab_search[$cpt][1] = $fic_search;
                            $cpt++;
                        }
                    }
                }
            }
            $fic_minuscptr = 0;
        }
        break;
    default:
        break;
}



// Construction de la Classe
if ($obj->File_Navigator($base, $tri_fma['tri'], $tri_fma['sens'], $dirsize_fma)) {

    // Current PWD and Url_back / match by OS determination
    if (substr(@php_uname(), 0, 7) == "Windows") {
        $cur_nav        = str_replace("\\", "/", $obj->Pwd());
        $cur_nav_back   = dirname($base);
    } else {
        $cur_nav        = $obj->Pwd();
        $cur_nav_back   = str_replace("\\", "/", dirname($base));
    }

    // contraint à rester dans la zone de répertoire définie (CHROOT)
    $cur_nav = $base . substr($cur_nav, strlen($base));

    $home = '/' . basename($basedir_fma);
    $cur_nav_href_back = "<a href=\"fmanager?FmaRep=$FmaRep&amp;browse=" . rawurlencode(Crypt::encrypt($cur_nav_back)) . "$urlext_fma\">" . str_replace(dirname($basedir_fma), "", $cur_nav_back) . "</a>/" . basename($cur_nav);
    
    if ($home_fma != '') {
        $cur_nav_href_back = str_replace($home, $home_fma, $cur_nav_href_back);
    }

    $cur_nav_encrypt = rawurlencode(Crypt::encrypt($cur_nav));
} else {
    // le répertoire ou sous répertoire est protégé (ex : chmod)
    Url::redirect("fmanager?FmaRep=$FmaRep&amp;browse=" . rawurlencode(Crypt::encrypt(dirname($base))));
}


// gestion des types d'extension de fichiers
$handle = opendir("$racine_fma/assets/images/upload/file_types");

while (false !== ($file = readdir($handle))) {
    if ($file != '.' && $file != '..') {
        $prefix = strtoLower(substr($file, 0, strpos($file, '.')));

        $att_icons[$prefix] = '<img src="assets/images/upload/file_types/' . $file . '" alt="" />'; // no more used keep if we back
        $att_icons[$prefix] = '
        <span class="fa-stack">
            <i class="bi bi-file-earmark fa-stack-2x text-muted"></i>
            <span class="fa-stack-1x filetype-text small ">' . $prefix . '</span>
        </span>';
    }
}
closedir($handle);

// Répertoires
$subdirs    = '';
$sizeofDir  = 0;

while ($obj->NextDir()) {
    if (Fmanager::fma_autorise('d', $obj->FieldName)) {
        $sizeofDir = 0;

        $subdirs .= '<tr>';

        $clik_url = "<a href=\"fmanager?FmaRep=$FmaRep&amp;browse=" . rawurlencode(Crypt::encrypt("$base/$obj->FieldName")) . "$urlext_fma\">";
        
        if ($dirpres_fma[0]) {
            $subdirs .= '<td width="3%" align="center">' . $clik_url . '<i class="bi bi-folder fs-3"></i></a></td>';
        }

        if ($dirpres_fma[1]) {
            $subdirs .= '<td nowrap="nowrap">' . $clik_url . Fmanager::extend_ascii($obj->FieldName) . '</a></td>';
        }

        if ($dirpres_fma[2]) {
            $subdirs .= '<td><small>' . $obj->FieldDate . '</small></td>';
        }

        if ($dirpres_fma[3]) {
            $sizeofD = $obj->FieldSize;
            $sizeofDir = $sizeofDir + (int)$sizeofD;
            $subdirs .= '<td class="d-none d-sm-table-cell"><small>' . $obj->ConvertSize($sizeofDir) . '</small></td>';
        } else {
            $subdirs .= '<td class="d-none d-sm-table-cell"><small>#NA#</small></td>';
        }

        if ($dirpres_fma[4]) {
            $subdirs .= '<td class="d-none d-sm-table-cell"><small>' . $obj->FieldPerms . '</small></td>';
        }

        // Traitements  
        $obj->FieldName = rawurlencode($obj->FieldName);

        $subdirs .= '<td class="">';

        if ($dircmd_fma[1]) {
            $subdirs .= '<a href="fmanager?FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=renamedir&amp;att_name=' . $obj->FieldName . '">
                <i class="bi bi-pencil-fill ms-2 fs-4" title="' . __d('fmanager', 'Renommer') . '" data-bs-toggle="tooltip"></i>
            </a>';
        }

        if ($dircmd_fma[3]) {
            $subdirs .= ' <a href="fmanager?FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=chmoddir&amp;att_name=' . $obj->FieldName . '">
                <i class="bi bi-pencil ms-3 fs-4" title="' . __d('fmanager', 'Chmoder') . '" data-bs-toggle="tooltip"></i><small>7..</small>
            </a>';
        }

        if ($dirpres_fma[5]) {
            $subdirs .= ' <a href="fmanager?FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=pict&amp;att_name=' . $obj->FieldName . '">
                <i class="bi bi-image-fill ms-3 fs-4" title="' . __d('fmanager', 'Autoriser Pic-Manager') . '" data-bs-toggle="tooltip"></i>
            </a>';
        }

        if ($dircmd_fma[2]) {
            $subdirs .= ' <a href="fmanager?FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=removedir&amp;att_name=' . $obj->FieldName . '">
                <i class="bi bi-trash2-fill text-danger ms-3 fs-4" title="' . __d('fmanager', 'Supprimer') . '" data-bs-toggle="tooltip"></i>
             </a>';
        }

        $subdirs .= '</td>
        </tr>';

        // Search Result for sub-directories
        if ($tab_search) {

            reset($tab_search);

            foreach ($tab_search as $l => $fic_resp) {
                if ($fic_resp[0] == $obj->FieldName) {
                    $ibid = rawurlencode(Crypt::encrypt(rawurldecode(encrypt($cur_nav . '/' . $fic_resp[0])) . '#fma#' . Crypt::encrypt($fic_resp[1])));

                    $subdirs .= '
                    <tr>
                        <td width="3%"></td>
                        <td>';

                    $pop = site_url('getfile?att_id='. $ibid .'&amp;apli=f-manager');
                    $target = "target=\"_blank\"";

                    if (!$wopen_fma) {
                        $subdirs .= "<i class=\"bi bi-search fs-4\"></i> <a href=$pop $target>" .Fmanager:: extend_ascii($fic_resp[1]) . "</a></td></tr>\n";
                    } else {
                        if (!isset($wopenH_fma)) {
                            $wopenH_fma = 500;
                        }

                        if (!isset($wopenW_fma)) {
                            $wopenW_fma = 400;
                        }

                        $PopUp = "$pop,'FManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$wopenH_fma,width=$wopenW_fma,toolbar=no,scrollbars=yes,resizable=yes'";
                        $subdirs .= "<i class=\"bi bi-search fs-4\"></i> <a href=\"javascript:void(0);\" onclick=\"popup=window.open($PopUp); popup.focus();\">" . Fmanager::extend_ascii($fic_resp[1]) . "</a></td></tr>\n";
                    }

                    array_splice($tab_search, $l, 1);
                }
            }
        }
    }
}

// Fichiers
$files = '';
$sizeofFic = 0;

while ($obj->NextFile()) {
    if (Fmanager::fma_autorise('f', $obj->FieldName)) {
        $ibid = rawurlencode(Crypt::encrypt($cur_nav_encrypt . "#fma#" . Crypt::encrypt($obj->FieldName)));
        
        $files .= '<tr>';

        if ($ficpres_fma[0]) {
            $ico_search = false;
            $files .= '<td width="3%" align="center">';

            if (!$ico_search) {
                foreach ($tab_search as $l => $fic_resp) {
                    if ($fic_resp[1] == $obj->FieldName) {
                        array_splice($tab_search, $l, 1);

                        $files .= '<i class="bi bi-search fs-4"></i>';
                        $ico_search = true;
                    }
                }
            }

            if (!$ico_search) {
                if (($obj->FieldView == 'jpg') or ($obj->FieldView == 'jpeg') or ($obj->FieldView == 'gif') or ($obj->FieldView == 'png') or ($obj->FieldView == 'svg')) {
                    $files .= "<img src=\"getfile.php?att_id=$ibid&amp;apli=f-manager\" width=\"32\" height=\"32\" loading=\"lazy\" />";
                } else {
                    if (isset($att_icons[$obj->FieldView])) {
                        $files .= $att_icons[$obj->FieldView];
                    } else {
                        $files .= '<span class="fa-stack">
                            <i class="bi bi-file-earmark fa-stack-2x text-muted"></i>
                            <span class="fa-stack-1x filetype-text ">?</span>
                        </span>';
                    }
                }
            }

            $files .= '</td>';
        }

        if ($ficpres_fma[1]) {
            if ($url_fma_modifier) {
                include("$racine_fma/modules/$ModPath/users/$FmaRep.mod.php");

                $pop = $url_modifier;
                $target = '';
            } else {
                $pop = "'getfile.php?att_id=$ibid&amp;apli=f-manager'";
                $target = 'target="_blank"';
            }

            if (!$wopen_fma) {
                $files .= "<td nowrap=\"nowrap\" width=\"50%\"><a href=$pop $target>" . Fmanager::extend_ascii($obj->FieldName) . "</a></td>";

            } else {
                if (!isset($wopenH_fma)) {
                    $wopenH_fma = 500;
                }

                if (!isset($wopenW_fma)) {
                    $wopenW_fma = 400;
                }

                $PopUp = "$pop,'FManager','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=$wopenH_fma,width=$wopenW_fma,toolbar=no,scrollbars=yes,resizable=yes'";
                
                if (stristr($PopUp, "window.opener")) {
                    $files .= "<td><a href=\"javascript:void(0);\" $PopUp popup.focus();\">" . Fmanager::extend_ascii($obj->FieldName) . "</a></td>";
                } else {
                    $files .= "<td><a href=\"javascript:void(0);\" onclick=\"popup=window.open($PopUp); popup.focus();\">" . Fmanager::extend_ascii($obj->FieldName) . "</a></td>";
                }
            }
        }

        if ($ficpres_fma[2]) {
            $files .= '<td><small>' . $obj->FieldDate . '</small></td>';
        }

        if ($ficpres_fma[3]) {
            $sizeofF = $obj->FieldSize;
            $sizeofFic = $sizeofFic + $sizeofF;
            $files .= '<td><small>' . $obj->ConvertSize($sizeofF) . '</small></td>';
        } else {
            $files .= '<td><small>#NA#</small></td>';
        }

        if ($ficpres_fma[4]) {
            $files .= '<td><small>' . $obj->FieldPerms . '</small></td>';
        } else {
            $files .= "<td><small>#NA#</small></td>";
        }

        // Traitements
        $obj->FieldName = rawurlencode($obj->FieldName);

        $cmd_ibid = '';

        if ($ficcmd_fma[1]) {
            $cmd_ibid .= '<a href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=renamefile&amp;att_name=' . $obj->FieldName . '"><i class="bi bi-pencil-fill ms-3 fs-4" title="' . __d('fmanager', 'Renommer') . '" data-bs-toggle="tooltip"></i></a>';
        }

        if ($ficcmd_fma[4]) {
            $tabW = explode(' ', $extension_Edit_fma);
            $suffix = strtoLower(substr(strrchr($obj->FieldName, '.'), 1));

            if (in_array($suffix, $tabW)) {
                $cmd_ibid .= '<a href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=editfile&amp;att_name=' . $obj->FieldName . '"><i class="bi bi-pencil-square ms-3 fs-4" title="' . __d('fmanager', 'Editer') . '" data-bs-toggle="tooltip"></i></a>';
            }
        }

        if ($ficcmd_fma[5]) {
            $cmd_ibid .= '<a href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=movefile&amp;att_name=' . $obj->FieldName . '"><i class="bi bi-box-arrow-up-right ms-3 fs-4" title="' . __d('fmanager', 'Déplacer / Copier') . '" data-bs-toggle="tooltip"></i></a>';
        }

        if ($ficcmd_fma[3]) {
            $cmd_ibid .= '<a href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=chmodfile&amp;att_name=' . $obj->FieldName . '"><i class="bi bi-pencil ms-3 fs-4" title="' . __d('fmanager', 'Chmoder') . '" data-bs-toggle="tooltip"></i><small>7..</small></a>';
        }

        if ($ficcmd_fma[2]) {
            $cmd_ibid .= '<a href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . $cur_nav_encrypt . '&amp;op=removefile&amp;att_name=' . $obj->FieldName . '"><i class="bi bi-trash2-fill text-danger  ms-3 fs-4" title="' . __d('fmanager', 'Supprimer') . '" data-bs-toggle="tooltip"></i></a>';
        }

        if ($cmd_ibid) {
            $files .= '<td>' . $cmd_ibid . '</td>';
        }

        $files .= '</tr>';
    }
}

chdir("$racine_fma/");


// vue generale

// Génération de l'interface
$inclusion = false;

if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/$theme_fma")) {
    $inclusion = "themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/$theme_fma";
} elseif (file_exists("themes/default/html/modules/f-manager/$theme_fma")) {
    $inclusion = "themes/default/html/modules/f-manager/$theme_fma";
} else {
    echo "html/modules/f-manager/$theme_fma manquant / not find !";
}

if ($inclusion) {
    $Xcontent = join('', file($inclusion));

    if ($FmaRep == 'minisite-ges') {
        if ($user) {
            $userdata = explode(':', base64_decode($user));
            $Xcontent = str_replace('_home', '<a class="nav-link" href="minisite.php?op=' . $userdata[1] . '" target="_blank"><i class="bi bi-display-fill fs-1"></i></a>', $Xcontent);
        }
    } else {
        $Xcontent = str_replace('_home', '<a class="nav-link" href="index.php" target="_blank"><span class="bi bi-house-fill fs-1 align-middle"></a>', $Xcontent);
    }

    $Xcontent = str_replace('_back', Fmanager::extend_ascii($cur_nav_href_back), $Xcontent);

    $Xcontent = str_replace('_refresh', '<a class="nav-link" href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=' . $ModStart . '&amp;FmaRep=' . $FmaRep . '&amp;browse=' . rawurlencode($browse) . $urlext_fma . '"><i class="bi bi-arrow-clockwise fs-1 d-sm-none" title="' . __d('fmanager', 'Rafraîchir') . '" data-bs-toggle="tooltip">
    </i><span class="d-none d-sm-block mt-2">' . __d('fmanager', 'Rafraîchir') . '</span></a>', $Xcontent);
    
    //   if ($dirsize_fma)
    $Xcontent = str_replace('_size', $obj->ConvertSize($obj->GetDirSize($cur_nav)), $Xcontent);
    //   else $Xcontent=str_replace("_size",'-',$Xcontent);
    
    $Xcontent = str_replace('_nb_subdir', ($obj->Count("d") - $dir_minuscptr), $Xcontent);
    
    if (($obj->Count("d") - $dir_minuscptr) == 0) {
        $Xcontent = str_replace('_tabdirclassempty', 'collapse', $Xcontent);
    }

    $Xcontent = str_replace('_subdirs', $subdirs, $Xcontent);
    $Xcontent = str_replace('_nb_file', ($obj->Count("f") - $fic_minuscptr), $Xcontent);
    $Xcontent = str_replace('_files', $files, $Xcontent);

    if (isset($cmd)) {
        $Xcontent = str_replace('_cmd', $cmd, $Xcontent);
    } else {
        $Xcontent = str_replace('_cmd', '', $Xcontent);
    }

    if ($dircmd_fma[0]) {

        $create_dir = '
        <form method="post" action="modules.php">
            <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
            <input type="hidden" name="browse" value="' . $browse . '" />
            <input type="hidden" name="op" value="createdir" />
            <div class="mb-3">
                <input class="form-control" name="userdir" type="text" value="" />
            </div>
            <input class="btn btn-primary" type="submit" name="ok" value="' . __d('fmanager', 'Ok') . '" />
        </form>';

        $Xcontent = str_replace('_cre_dir', $create_dir, $Xcontent);
    } else {
        $Xcontent = str_replace('_classcredirno', 'collapse', $Xcontent);
        $Xcontent = str_replace('<div id="cre_dir">', '<div id="cre_dir" style="display: none;">', $Xcontent);
        $Xcontent = str_replace('_cre_dir', '', $Xcontent);
    }

    $Xcontent = str_replace('_del_dir', $remove_dir, $Xcontent);
    $Xcontent = str_replace('_ren_dir', $rename_dir, $Xcontent);
    $Xcontent = str_replace('_chm_dir', $chmod_dir, $Xcontent);

    if (isset($pict_dir)) {
        $Xcontent = str_replace('_pic_dir', $pict_dir, $Xcontent);
    } else {
        $Xcontent = str_replace("_pic_dir", '', $Xcontent);
    }

    if ($ficcmd_fma[0]) {

        $create_file = '
        <form method="post" action="modules.php">
            <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
            <input type="hidden" name="browse" value="' . $browse . '" />
            <input type="hidden" name="op" value="createfile" />
            <div class="mb-3">
                <input class="form-control" name="userfile" type="text" value="" />
            </div>
            <input class="btn btn-primary" type="submit" name="ok" value="' . __d('fmanager', 'Ok') . '" />
        </form>';

        $upload_file = '
        <form id="uploadfichier" enctype="multipart/form-data" method="post" action="modules.php" lang="' . Language::language_iso(1, '', '') . '">
            <input type="hidden" name="FmaRep" value="' . $FmaRep . '" />
            <input type="hidden" name="browse" value="' . $browse . '" />
            <input type="hidden" name="op" value="upload" />
            <div class="mb-3">
                <div class="help-block mb-2">' . __d('fmanager', 'Extensions autorisées : ') . '<span class="text-success">' . $extension_fma . '</span></div>
                <div class="input-group mb-3 me-sm-2">
                    <button class="btn btn-secondary" type="button" onclick="reset2($(\'#userfile\'),\'\');"><i class="bi bi-arrow-clockwise"></i></button>
                    <label class="input-group-text n-ci" id="lab" for="userfile"></label>
                    <input type="file" class="form-control custom-file-input" name="userfile" id="userfile" />
                </div>
                <button class="btn btn-primary" type="submit" name="ok" ><i class="bi bi-upload"></i></button>
            </div>
        </form>
        <script type="text/javascript">
            //<![CDATA[
                window.reset2 = function (e,f) {
                    e.wrap("<form>").closest("form").get(0).reset();
                    e.unwrap();
                    event.preventDefault();
                };
            //]]>
        </script>';

        $Xcontent = str_replace('_upl_file', $upload_file, $Xcontent);
        $Xcontent = str_replace('_cre_file', $create_file, $Xcontent);
    } else {
        $Xcontent = str_replace('_classuplfileno', 'collapse', $Xcontent);
        $Xcontent = str_replace('<div id="upl_file">', '<div id="upl_file" style="display: none;">', $Xcontent);
        $Xcontent = str_replace('_classcrefileno', 'collapse', $Xcontent);
        $Xcontent = str_replace('<div id="cre_file">', '<div id="cre_file" style="display: none;">', $Xcontent);
        $Xcontent = str_replace('_upl_file', '', $Xcontent);
        $Xcontent = str_replace('_cre_file', '', $Xcontent);
    }

    $search_file = '
    <form method="post" action="modules.php">
        <input type="hidden" name="FmaRep" value="' . $FmaRep . '">
        <input type="hidden" name="browse" value="' . $browse . '">
        <input type="hidden" name="op" value="searchfile">
        <div class="mb-3">
            <input class="form-control" name="filesearch" type="text" size="50" value="">
        </div>
        <input class="btn btn-primary" type="submit" name="ok" value="' . __d('fmanager', 'Ok') . '">
    </form>';

    $Xcontent = str_replace('_sea_file', $search_file, $Xcontent);
    $Xcontent = str_replace('_del_file', $remove_file, $Xcontent);
    $Xcontent = str_replace('_chm_file', $chmod_file, $Xcontent);
    $Xcontent = str_replace('_ren_file', $rename_file, $Xcontent);
    $Xcontent = str_replace('_mov_file', $move_file, $Xcontent);

    if (isset($Err)) {
        $Xcontent = str_replace('_error', '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $Err . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>', $Xcontent);
    } else {
        $Xcontent = str_replace('_error', '', $Xcontent);
    }

    if (file_exists($infos_fma)) {
        $infos = Language::aff_langue(join('', file($infos_fma)));
    }

    if (isset($infos)) {
        $Xcontent = str_replace('_infos', $infos, $Xcontent);
    } else {
        $Xcontent = str_replace('_infos', '', $Xcontent);
    }

    if ($dirpres_fma[5]) {
        if ($uniq_fma) { 
            $Xcontent = str_replace('_picM', '<a class="nav-link" href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=pic-manager&amp;FmaRep=' . $FmaRep . '&amp;browse=' . rawurlencode($browse) . '"><span class="d-sm-none"><i class="bi bi-image fs-1" title="' . __d('fmanager', 'Images manager') . '" data-bs-toggle="tooltip" data-bs-placement="bottom"></i></span><span class="d-none d-sm-block mt-2">' . __d('fmanager', 'Images manager') . '</span></a>', $Xcontent);
        } else {
            $Xcontent = str_replace('_picM', '<a class="nav-link" href="modules.php?ModPath=' . $ModPath . '&amp;ModStart=pic-manager&amp;FmaRep=' . $FmaRep . '&amp;browse=' . rawurlencode($browse) . '" target="_blank"><span class="d-sm-none"><i class="fa fa-image fa-lg"></i></span><span class="d-none d-sm-block mt-2">' . __d('fmanager', 'Images manager') . '</span></a>', $Xcontent);
        }
    } else {
        $Xcontent = str_replace('_picM', '', $Xcontent);
    }

    $Xcontent = str_replace('_quota', $obj->ConvertSize($sizeofDir + $sizeofFic) . ' || ' . __d('fmanager', 'Taille maximum d\'un fichier : ') . $obj->ConvertSize($max_size), $Xcontent);

    if (!$App_fma) {

        // utilisation de pages.php
        // settype($PAGES, 'array');

        // require_once("themes/pages.php");

        $Titlesitename = aff_langue($PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['title']);

        // global $user;
        // if (isset($user) and $user != '') {

        //     global $cookie;
        //     if ($cookie[9] != '') {
        //         $ibix = explode('+', urldecode($cookie[9]));

        //         if (array_key_exists(0, $ibix)) {
        //             $theme = $ibix[0];
        //         } else {
        //             $theme = Config::get('npds.Default_Theme');
        //         }

        //         if (array_key_exists(1, $ibix)) {
        //             $skin = $ibix[1];
        //         } else {
        //             $skin = Config::get('npds.Default_Skin'); //$skin='';
        //             } 

        //         $tmp_theme = $theme;

        //         if (!$file = @opendir("themes/$theme")) {
        //             $tmp_theme = Config::get('npds.Default_Theme');
        //         }
        //     } else {
        //         $tmp_theme = Config::get('npds.Default_Theme');
        //     }
        // } else {
        //     $theme = Config::get('npds.Default_Theme');
        //     $skin = Config::get('npds.Default_Skin');
        //     $tmp_theme = $theme;
        // }

        include("storage/meta/meta.php");

        echo '
        <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="assets/font-awesome/css/all.min.css" />
        <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap-icons.css" />
        <link rel="stylesheet" id="fw_css" href="assets/skins/' . $skin . '/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/bootstrap-table/dist/bootstrap-table.min.css" />
        <link rel="stylesheet" id="fw_css_extra" href="assets/skins/' . $skin . '/extra.css" />
        <link rel="stylesheet" href="' . $css_fma . '" title="default" type="text/css" media="all" />';


        if (Config::get('npds.tiny_mce')) {
            $tiny_mce_init = $PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce'];

            if ($tiny_mce_init) {
                $tiny_mce_theme = $PAGES["modules.php?ModPath=$ModPath&ModStart=$ModStart*"]['TinyMce-theme'];
                echo Editeur::aff_editeur("tiny_mce", "begin");
            }
        }

        echo '
        <script type="text/javascript" src="assets/shared/jquery/jquery.min.js"></script>
        </head>
        <body class="p-3">';
    } 
    // else {
    //     include("header.php");
    // }

    // Head banner de présentation F-Manager
    if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/head.html")) {
        echo "\n";
        include("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/head.html");
        echo "\n";
    } else if (file_exists("themes/default/html/modules/f-manager/head.html")) {
        echo "\n";
        include("themes/default/html/modules/f-manager/head.html");
        echo "\n";
    }

    ?>
    <script type="text/javascript">
        //<![CDATA[
        function previewImage(fileInfo) {
            var filename = '';
            filename = fileInfo;

            //create the popup
            popup = window.open('', 'imagePreview', 'width=600,height=450,left=100,top=75,screenX=100,screenY=75,scrollbars,location,menubar,status,toolbar,resizable=1');

            //start writing in the html code
            popup.document.writeln("<html><body style='background-color: #FFFFFF;'>");
            popup.document.writeln("<img src='" + filename + "'></body></html>");
        }
        //]]>
    </script>
    <?php

    // l'insertion de la FORM d'édition doit intervenir à la fin du calcul de l'interface ... sinon on modifie le contenu
    // Meta_lang n'est pas chargé car trop lent pour une utilisation sur de gros répertoires
    $Xcontent = Language::aff_langue($Xcontent);
    $Xcontent = str_replace('_edt_file', $edit_file, $Xcontent);

    echo $Xcontent;

    // Foot banner de présentation F-Manager
    if (file_exists("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/foot.html")) {
        echo "\n";
        include("themes/".Config::get('npds.Default_Theme')."/html/modules/f-manager/foot.html");
        echo "\n";
    } else if (file_exists("themes/default/html/modules/f-manager/foot.html")) {
        echo "\n";
        include("themes/default/html/modules/f-manager/foot.html");
        echo "\n";
    }

    if (!$npds_fma) {
        echo '
        </body>
        </html>';

        if (Config::get('npds.tiny_mce')) {
            if ($tiny_mce_init) {
                echo Editeur::aff_editeur("tiny_mce", "end");
            }
        }
    } 
    // else {
    //     include("footer.php");
    // }
}
?>