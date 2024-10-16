<?php

/************************************************************************/
/* DUNE by App                                                         */
/* ===========================                                          */
/*                                                                      */
/* Session and log Viewer Copyright (c) 2009 - Tribal-Dolphin           */
/*                                                                      */
/* App Copyright (c) 2002-2024 by Philippe Brunier                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 3 of the License.       */
/************************************************************************/

if (!function_exists('admindroits'))
    include($_SERVER['DOCUMENT_ROOT'] . '/admin/die.php');

$f_meta_nom = 'session_log';

//==> controle droit
admindroits($aid, $f_meta_nom);
//<== controle droit

global $ModPath, $ModStart;

$hlpfile = 'language/manuels/' . Config::get('npds.language') . '/logs.html';

include('modules/upload/upload.conf.php');
include('modules/geoloc/geoloc_locip.php');

if ($DOCUMENTROOT == '') {
    global $DOCUMENT_ROOT;
    if ($DOCUMENT_ROOT)
        $DOCUMENTROOT = $DOCUMENT_ROOT;
    else
        $DOCUMENTROOT = $_SERVER['DOCUMENT_ROOT'];
}

$FileSecure = $DOCUMENTROOT . $racine . '/storage/logs/security.log';
$FileUpload = $DOCUMENTROOT . $rep_log;
$RepTempFil = $DOCUMENT_ROOT . $rep_cache;

include("modules/$ModPath/lang/session-log-Config::get('npds.language').php");

$ThisFile = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=" . $ModPath . "&amp;ModStart=" . $ModStart;
$f_titre = __d('npds', 'Gestion des Logs');

settype($subop, 'string');

function action_log($ThisFile, $logtype)
{
    global $FileSecure, $FileUpload, $RepTempFil, $rep_cache;

    $whatlog = 'security';

    if ($FileUpload != $FileSecure) 
        $whatlog = 'upload';

    $task = '
      <a class="dropdown-item" href="' . $ThisFile . '&amp;subop=mailog&amp;log=' . $whatlog . '"><i class="fa fa-at me-1 fa-lg"></i>' . __d('npds', 'Recevoir le fichier par mail') . '</a>
      <a class="dropdown-item" href="' . $ThisFile . '&amp;subop=vidlog&amp;log=' . $whatlog . '"><i class="fa fa-times me-1 fa-lg"></i>' . __d('npds', 'Vider le fichier') . '<br /><small>' . $FileSecure . '</small></a>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item" href="' . $ThisFile . '&amp;subop=vidtemp"><i class="fas fa-trash me-1 fa-lg"></i>' . __d('npds', 'Effacer les fichiers temporaires') . '<br /><small>' . $rep_cache . '</small></a>';
    
    return $task;
}

GraphicAdmin($hlpfile);
adminhead($f_meta_nom, $f_titre);

$cl_a_ses = $subop == "session" ? 'active' : '';
$cl_a_sec = $subop == "security" ? 'active' : '';

echo '
    <hr />
    <ul class="nav nav-tabs">
    <li class="nav-item"><a href="' . $ThisFile . '&subop=session" class="nav-link ' . $cl_a_ses . '">' . __d('npds', 'Liste des Sessions') . '</a></li>
    <li class="nav-item"><a href="' . $ThisFile . '&subop=security" class="nav-link ' . $cl_a_sec . '">' . __d('npds', 'Liste des Logs') . '</a>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench fa-lg"></i></a>
        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
        ' . action_log($ThisFile, '') . '
        </div>
    </li>
    </ul>';

// Voir les sessions
if ($subop == 'session') {
    echo '
        <br />
        <h3>' . __d('npds', 'Liste des Sessions') . ' : <code>TABLE session</code></h3>
        <table id="tad_ses" data-classes="table table-sm table-striped table-borderless" data-toggle="table" data-show-toggle="true" data-search="true" data-mobile-responsive="true" data-buttons-class="outline-secondary" data-icons-prefix="fa" data-icons="icons">
            <thead>
                <tr>
                <th class="n-t-col-xs-1" data-halign="center" data-align="center" data-sortable="true">' . __d('npds', 'Nom') . '</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">@ IP</th>
                <th class="n-t-col-xs-2" data-halign="center" data-align="right" data-sortable="true">' . __d('npds', '@ IP résolue') . '</th>
                <th data-halign="center" data-sortable="true">URI</th>
                <th class="n-t-col-xs-1" data-halign="center" data-align="center" data-sortable="true">' . __d('npds', 'Agent') . '</th>
                </tr>
            </thead>
            <tbody>';

    $result = sql_query("SELECT username, host_addr, guest, uri, agent FROM session");
    
    while (list($username, $host_addr, $guest, $uri, $agent) = sql_fetch_row($result)) {
        
        if ($username == $host_addr) {
            $username = Config::get('npds.anonymous');
        }

        if (preg_match('#(crawl|bot|spider|yahoo)#', strtolower($agent))) 
            $agent = "Bot";
        else 
            $agent = "Browser";

        echo '
            <tr>
               <td class="small">' . $username . '</td>
               <td class="small">' . urldecode($host_addr) . '</td>
               <td class="small">' . gethostbyaddr(urldecode($host_addr)) . '</td>
               <td class="small">' . $uri . '</td>
               <td class="small">' . $agent . '</td>
           </tr>';
    }

    echo '
            </tbody>
        </table>';
}

// Détails @IP
if ($subop == 'info') {
    echo '
        <br />
        <h3>' . __d('npds', 'Informations sur l\'IP') . '</h3>';

    $hostname = gethostbyaddr(urldecode($theip));

    settype($provider, 'string');

    if ($theip != $hostname) {
        $domfai = explode('.', $hostname);
        $prov = $domfai[count($domfai) - 2] . '.' . $domfai[count($domfai) - 1];

        if ($prov == 'co.jp' or $prov == 'co.uk')
            $provider = $domfai[sizeof($domfai) - 3] . '.' . $prov;
        else
            $provider = $prov;
    } else
        $hostname = $theip;

    echo '
        <div class="card card-body">
            <div class="row">
                <div class="col mb-3">
                <span class="text-muted">' . __d('npds', '@ IP') . '</span> : <span>' . $theip . '</span><br />
                <span class="text-muted">' . __d('npds', '@ IP résolue') . '</span> : <span>' . $hostname . '</span><br />
                <span class="text-muted">' . __d('npds', 'Fournisseur') . '</span> : <span>' . $provider . '</span><br />
                </div>';

    echo localiser_ip($iptoshow = $theip);

    echo '
            </div>
        </div>';

    $subop = 'security';
}

// Vider les Logs
if ($subop == 'vidlog') {
    if ($log == 'security') {
        if (file_exists($FileSecure)) {
            $File_Secure = fopen($FileSecure, "w");
            fclose($File_Secure);
        }
    }

    if ($log == 'upload') {
        if (file_exists($FileUpload)) {
            $File_Upload = fopen($FileUpload, "w");
            fclose($File_Upload);
        }
    }
}

// Email du contenu des Logs
if ($subop == 'mailog') {
    if ($log == 'security')
        if (file_exists($FileSecure))
            $Mylog = $FileSecure;

    if ($log == 'upload')
        if (file_exists($FileUpload))
            $Mylog = $FileUpload;

    $file = [
        'file' => $Mylog,
        'name' => 'security.log',
    ];

    $subject = html_entity_decode(__d('npds', 'Fichier de Log de'), ENT_COMPAT | ENT_HTML401, cur_charset) . ' ' . Config::get('npds.sitename');
    $message = __d('npds', 'Fichier de Log de') . ' ' . Config::get('npds.sitename') . "<br /><br />";

    send_email(Config::get('npds.adminmail'), $subject, $message, Config::get('npds.adminmail'), true, 'mixed', $file);
}

// Vider le répertoire temporaire
if ($subop == 'vidtemp') {
    if (is_dir($RepTempFil)) {
        $dh = opendir($RepTempFil);

        $i = 0;

        while (false !== ($filename = readdir($dh))) {
            if ($filename === '.' or $filename === '..' or $filename === 'index.html') 
                continue;

            @unlink($RepTempFil . $filename);
        }
    }
}

// Voir le contenu du fichier security.log
if ($subop == 'security') {
    $UpLog = '';
    $SecLog = '';

    if (file_exists($FileSecure)) {
        if (filesize($FileSecure) != 0) {

            $fd = fopen($FileSecure, "r");

            while (!feof($fd)) {
                $buffer = fgets($fd, 4096);

                if (strlen($buffer) > 10) {
                    if (stristr($buffer, 'Upload'))
                        $UpLog .= '
                        <tr>
                            <td style="font-size:10px;">' . $buffer . '</td>
                        </tr>';
                    else {
                        $ip = substr(strrchr($buffer, "=>"), 2);
                        $SecLog .= '
                        <tr>
                            <td class="small">' . $buffer . '</td>
                            <td><a href="' . $ThisFile . '&amp;subop=info&amp;theip=' . $ip . '" >' . __d('npds', 'Infos') . '</a></td>
                        </tr>';
                    }
                }
            }
            fclose($fd);
        }
    }

    echo '
        <h3 class="my-3"><a data-bs-toggle="collapse" href="#tog_tad_slog" aria-expanded="false" aria-controls="tog_tad_slog"><i class="toggle-icon fa fa-caret-down"></i></a><span class="ms-2">' . __d('npds', 'Liste des Logs') . ' ' . __d('npds', 'SECURITE') . ' : <code>security.log</code></span></h3>
        <div id="tog_tad_slog" class="collapse">
            <table id="tad_slog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true">
                <thead>
                <tr>
                    <th class="n-t-col-xs-10" data-sortable="true">Logs</th>
                    <th data-align="center" class="n-t-col-xs-2">Fonctions</th>
                </tr>
                </thead>
                <tbody>
                ' . $SecLog . '
                </tbody>
            </table>
        </div>
        <h3 class="mt-3"><a data-bs-toggle="collapse" href="#tog_tad_tlog" aria-expanded="false" aria-controls="tog_tad_tlog"><i class="toggle-icon fa fa-caret-down"></i></a><span class="ms-2">' . __d('npds', 'Liste des Logs') . ' ' . __d('npds', 'TELECHARGEMENT') . ' : <code>security.log</code></span></h3>
        <div id="tog_tad_tlog" class="collapse">
            <table id="tad_tlog" data-toggle="table" data-striped="true" data-search="true" data-mobile-responsive="true" data-icons="icons" data-icons-prefix="fa">
                <thead>
                <tr>
                    <th>Logs</th>
                </tr>
                </thead>
                <tbody>
                ' . $UpLog . '
                </tbody>
            </table>
        </div>';

    action_log($ThisFile, 'security');
}

// Voir le contenu du fichier d'upload si différent de security.log (upload.conf.php)
if ($subop == "upload") {
    $UpLog = '';

    if ($FileUpload != $FileSecure) {
        if (file_exists($FileUpload)) {
            if (filesize($FileUpload) != 0) {

                $fd = @fopen($FileUpload, "r");

                while (!feof($fd)) {
                    $buffer = fgets($fd, 4096);
                    $UpLog .= '
                    <tr>
                    <td style="font-size:0.65rem;">' . $buffer . '</td></tr>';
                }
                @fclose($fd);
            }
        }

        echo __d('npds', 'Liste des Logs') . ' ' . __d('npds', 'TELECHARGEMENT') . ' : <code class="code">' . $FileUpload . '</code>';
        echo '
            <table>
            ' . $UpLog . '
            </table>';

        action_log($ThisFile, "upload");
    }
}

adminfoot('', '', '', '');
