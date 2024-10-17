<?php

namespace Modules\Upload\Library;

use Npds\Filesystem\FileManagement;
use Modules\Upload\Library\FileUpload;
use Modules\Upload\Contracts\UploadInterface;


/**
 * Undocumented class
 */
class NpdsUploadManager implements UploadInterface 
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
     * Undocumented function
     *
     * @return void
     */
    public function editeur_upload()
    {
        global $apli, $pcfile, $pcfile_size, $pcfile_name, $pcfile_type;
        global $MAX_FILE_SIZE, $MAX_FILE_SIZE_TOTAL, $mimetypes, $mimetype_default, $rep_upload_editeur, $path_upload_editeur;

        // Récupération des valeurs de PCFILE
        global $HTTP_POST_FILES, $_FILES;

        if (!empty($HTTP_POST_FILES)) {
            $fic = $HTTP_POST_FILES;
        } else {
            $fic = $_FILES;
        }

        $pcfile_name    = $fic['pcfile']['name'];
        $pcfile_type    = $fic['pcfile']['type'];
        $pcfile_size    = $fic['pcfile']['size'];
        $pcfile         = $fic['pcfile']['tmp_name'];
        $pcfile_error   = $fic['pcfile']['error'];


        $_FILES['pcfile']['name'];
        $_FILES['pcfile']['type'];
        $_FILES['pcfile']['size'];
        $_FILES['pcfile']['tmp_name'];
        $_FILES['pcfile']['error'];

        $fu = new FileUpload;
        $fu->init($rep_upload_editeur, '', $apli);

        $attachments = $fu->getUploadedFiles('', '');

        if (is_array($attachments)) {
            // $att_count  = $attachments['att_count']; // not used
            // $att_size   = $attachments['att_size']; // not used

            if (is_array($pcfile_name)) {
                reset($pcfile_name);

                $pcfile_name = implode(', ', $pcfile_name);
            }
            
            return ($path_upload_editeur . $pcfile_name);
        } else {
            return '';
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function forum_upload()
    {
        // global $apli, $IdPost, $IdForum, $IdTopic, $pcfile, $pcfile_size, $pcfile_name, $pcfile_type, $att_count, $att_size, $total_att_count, $total_att_size;
        // global $MAX_FILE_SIZE, $MAX_FILE_SIZE_TOTAL, $mimetypes, $mimetype_default, $upload_table, $rep_upload_forum; // mine......

        list($sum) = sql_fetch_row(sql_query("SELECT SUM(att_size ) FROM $upload_table WHERE apli = '$apli' AND post_id = '$IdPost'"));

        // gestion du quota de place d'un post
        if (($MAX_FILE_SIZE_TOTAL - $sum) < $MAX_FILE_SIZE) {
            $MAX_FILE_SIZE = $MAX_FILE_SIZE_TOTAL - $sum;
        }

        settype($thanks_msg, 'string');

        // Récupération des valeurs de PCFILE
        global $HTTP_POST_FILES, $_FILES;

        if (!empty($HTTP_POST_FILES)) {
            $fic = $HTTP_POST_FILES;
        } else {
            $fic = $_FILES;
        }

        $pcfile_name    = $fic['pcfile']['name'];
        $pcfile_type    = $fic['pcfile']['type'];
        $pcfile_size    = $fic['pcfile']['size'];
        $pcfile         = $fic['pcfile']['tmp_name'];

        $fu = new FileUpload;
        $fu->init($rep_upload_forum, $IdForum, $apli);

        $att_count  = 0;
        $att_size   = 0;

        $total_att_count    = 0;
        $total_att_size     = 0;

        $attachments = $fu->getUploadedFiles($IdPost, $IdTopic);

        if (is_array($attachments)) {
            $att_count = $attachments['att_count'];
            $att_size = $attachments['att_size'];

            if (is_array($pcfile_name)) {
                reset($pcfile_name);

                $names = implode(', ', $pcfile_name);
                $pcfile_name = $names;
            }

            $pcfile_size = $att_size;

            $thanks_msg .= '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                ' . str_replace('{NAME}', '<strong>' . $pcfile_name . '</strong>',
                 str_replace('{SIZE}', $pcfile_size, __d('upload', 'Fichier {NAME} bien reçu ({SIZE} octets transférés)'))) . '</div>';
            
            $total_att_count    += $att_count;
            $total_att_size     += $att_size;
        }
        
        return $thanks_msg;
    }

    /**
     * Fonction pour charger en mémoire les mimetypes 
     *
     * @return void
     */
    public function load_mimetypes()
    {
        global $mimetypes, $mimetype_default, $mime_dspinl, $mime_dspfmt, $mime_renderers, $att_icons, $att_icon_default, $att_icon_multiple;

        if (defined('ATT_DSP_LINK')) {
            return;
        }

        if (file_exists("modules/upload/include/mimetypes.php")) {
            include("modules/upload/include/mimetypes.php");
        }
    }

    /**
     * Fonction qui retourne ou la liste ou l'attachement voulu
     *
     * @param [type] $apli
     * @param [type] $post_id
     * @param integer $att_id
     * @param integer $Mmod
     * @return string
     */
    public function getAttachments($apli, $post_id, $att_id = 0, $Mmod = 0)
    {
        global $upload_table;

        $query = "SELECT att_id, att_name, att_type, att_size, att_path, inline, compteur, visible FROM $upload_table WHERE apli='$apli' && post_id='$post_id'";
        
        if ($att_id > 0) {
            $query .= " AND att_id=$att_id";
        }

        if (!$Mmod) {
            $query .= " AND visible=1";
        }

        $query .= " ORDER BY att_type,att_name";
        $result = sql_query($query);

        $i = 0;
        while ($attach = sql_fetch_assoc($result)) {
            $att[$i] = $attach;
            $i++;
        }

        return ($i == 0) ? '' : $att;
    }

    // Pour la class

    /**
     * Ajoute l'attachement dans la base de donnée
     *
     * @param [type] $apli
     * @param [type] $IdPost
     * @param [type] $IdTopic
     * @param [type] $IdForum
     * @param [type] $name
     * @param [type] $path
     * @param string $inline
     * @param integer $size
     * @param string $type
     * @return void
     */
    public function insertAttachment($apli, $IdPost, $IdTopic, $IdForum, $name, $path, $inline = "A", $size = 0, $type = "")
    {
        global $upload_table, $visible_forum;

        $size = empty($size) ? filesize($path) : $size;
        $type = empty($type) ? "application/octet-stream" : $type;

        $stamp = time();

        $sql = "INSERT INTO $upload_table VALUES ('', '$IdPost', '$IdTopic','$IdForum', '$stamp', '$name', '$type', '$size', '$path', '1', '$apli', '0', '$visible_forum')";
        $ret = sql_query($sql);
        
        if (!$ret) {
            return -1;
        }

        return sql_last_id();
    }

    /**
     * Suprime l'attachement dans la base de donné en cas d erreur d'upload
     *
     * @param [type] $apli
     * @param [type] $IdPost
     * @param [type] $upload_dir
     * @param [type] $id
     * @param [type] $att_name
     * @return void
     */
    public function deleteAttachment($apli, $IdPost, $upload_dir, $id, $att_name)
    {
        global $upload_table;

        @unlink("$upload_dir/$id.$apli.$att_name");

        $sql = "DELETE FROM $upload_table WHERE att_id= '$id'";
        sql_query($sql);
    }

    // Pour la visualisation dans les forum

    /**
     * Fonction de snipe pour l'affichage des fichier uploader dans forums
     *
     * @param [type] $apli
     * @param [type] $post_id
     * @param [type] $Mmod
     * @return void
     */
    public function display_upload($apli, $post_id, $Mmod)
    {
        $att_size = '';
        $att_type = '';
        $att_name = '';
        $att_url = '';
        $att_link = '';
        $attachments = '';
        $att_icon = '';
        $num_cells = 5;

        $att = $this->getAttachments($apli, $post_id, 0, $Mmod);

        if (is_array($att)) {
            $att_count = count($att);

            $attachments = '
            <div class="list-group">
                <div class="list-group-item d-flex justify-content-start align-items-center mt-2">
                    <img class="n-smil" src="assets/images/forum/subject/07.png" alt="icon_pieces jointes" />
                    <span class="text-muted p-2">' . __d('upload', 'Pièces jointes') . '</span><a data-bs-toggle="collapse" href="#lst_pj' . $post_id . '"><i data-bs-toggle="tooltip" data-bs-placement="top" title="" class="toggle-icon fa fa-lg me-2 fa-caret-up"></i></a>
                    <span class="badge bg-secondary ms-auto">' . $att_count . '</span>
                </div>
                <div id="lst_pj' . $post_id . '" class="collapse show">';

            $ncell = 0;

            for ($i = 0; $i < $att_count; $i++) {
                $att_id        = $att[$i]["att_id"];
                $att_name      = $att[$i]["att_name"];
                $att_path      = $att[$i]["att_path"];
                $att_type      = $att[$i]["att_type"];
                $att_size      = (int) $att[$i]["att_size"];
                $compteur      = $att[$i]["compteur"];
                $visible       = $att[$i]["visible"];
                $att_inline    = $att[$i]["inline"];

                if (!$visible) {
                    $marqueurV = "@";
                } else {
                    $marqueurV = '';
                }

                $att_link      = $this->getAttachmentUrl($apli, $post_id, $att_id, "$att_path/$att_id.$apli." . $marqueurV . "$att_name", $att_type, $att_size, $att_inline, $compteur, $visible, $Mmod);
                $attachments .= $att_link;
                $att_list[$att_id] = $att_name;
            }

            $attachments .= '
                </div>
            </div>';

            return $attachments;
        }
    }

    /**
     * Retourne l'attachement
     *
     * @param [type] $apli
     * @param [type] $post_id
     * @param [type] $att_id
     * @param [type] $att_path
     * @param [type] $att_type
     * @param [type] $att_size
     * @param integer $att_inline
     * @param [type] $compteur
     * @param integer $visible
     * @param [type] $Mmod
     * @return void
     */
    public function getAttachmentUrl($apli, $post_id, $att_id, $att_path, $att_type, $att_size, $att_inline = 0, $compteur, $visible = 0, $Mmod)
    {
        global $icon_dir, $img_dir, $forum, $mimetype_default, $mime_dspfmt, $mime_renderers, $DOCUMENTROOT;

        $this->load_mimetypes();

        $att_name = substr(strstr(basename($att_path), '.'), 1);
        $att_name = substr(strstr(basename($att_name), '.'), 1);

        $att_path = $DOCUMENTROOT . $att_path;

        if (!is_file($att_path)) {
            return '&nbsp;<span class="text-danger" style="font-size: .65rem;">' . __d('upload', 'Fichier non trouvé') . ' : ' . $att_name . '</span>';
        }

        if ($att_inline) {
            if (isset($mime_dspfmt[$att_type])) {
                $display_mode = $mime_dspfmt[$att_type];
            } else {
                $display_mode = $mime_dspfmt[$mimetype_default];
            }
        } else {
            $display_mode = ATT_DSP_LINK;
        }

        if ($Mmod) {
            global $userdata;
            $marqueurM = '&amp;Mmod=' . substr($userdata[2], 8, 6);
        } else {
            $marqueurM = '';
        }

        $att_url = "getfile.php?att_id=$att_id&amp;apli=$apli" . $marqueurM . "&amp;att_name=" . rawurlencode($att_name);

        settype($visible_wrn, 'string');

        if ($visible != 1) {
            $visible_wrn = '&nbsp;<span class="text-danger" style="font-size: .65rem;">' . __d('upload', 'Fichier non visible') . '</span>';
        }

        switch ($display_mode) {

            // display as an embedded image
            case ATT_DSP_IMG: 
                $size           = @getImageSize("$att_path");
                $img_size       = 'style="max-width: 100%; height:auto;" loading="lazy" ';
                $text           = str_replace('"', '\"', $mime_renderers[ATT_DSP_IMG]);
                
                eval("\$ret=stripSlashes(\"$text\");");
                break;

            // display as embedded text, PRE-formatted    
            case ATT_DSP_PLAINTEXT: 
                $att_contents   = str_replace("\\", "\\\\", htmlSpecialChars(join('', file($att_path)), ENT_COMPAT | ENT_HTML401, cur_charset));
                $att_contents   = $this->word_wrap($att_contents);
                $text           = str_replace('"', '\"', $mime_renderers[ATT_DSP_PLAINTEXT]);

                eval("\$ret=\"$text\";");
                break;

            // display as embedded HTML text    
            case ATT_DSP_HTML: 
                //au choix la source ou la page
                $att_contents   = $this->word_wrap(nl2br($this->scr_html(join("", file($att_path)))));
                //$att_contents = removeHack (join ("", file ($att_path)));
                $text           = str_replace('"', '\"', $mime_renderers[ATT_DSP_HTML]);

                eval("\$ret=stripSlashes(\"$text\");");
                break;

            // Embedded Macromedia Shockwave Flash    
            case ATT_DSP_SWF: 
                $size           = @getImageSize("$att_path");
                $img_size       = $this->verifsize($size);
                $text           = str_replace('"', '\"', $mime_renderers[ATT_DSP_SWF]);

                eval("\$ret=stripSlashes(\"$text\");");
                break;

            // display as link    
            default: 
                $Fichier    = new FileManagement;
                $att_size   = $Fichier->file_size_format($att_size, 1);
                $att_icon   = $this->att_icon($att_name);
                $text       = str_replace('"', '\"', $mime_renderers[ATT_DSP_LINK]);

                eval("\$ret=stripSlashes(\"$text\");");
                break;
        }

        // ret retour eval
        return $ret;
    }



    /**
     * Retourne Le mode d affichage pour un attachement
     * 
     * 1   display as icon (link)
     * 2   display as image
     * 3   display as embedded HTML text or the source
     * 4   display as embedded text, PRE-formatted
     * 5   display as flash animation
     * 
     * @param [type] $att_type
     * @param string $att_inline
     * @return void
     */
    public function getAttDisplayMode($att_type, $att_inline = "A")
    {
        global $mime_dspfmt, $mimetype_default, $ext;

        $this->load_mimetypes();

        if ($att_inline) {
            if (isset($mime_dspfmt[$att_type])) {
                $display_mode = $mime_dspfmt[$att_type];
            } else {
                $display_mode = $mime_dspfmt[$mimetype_default];
            }
        } else {
            $display_mode = ATT_DSP_LINK;
        }

        return $display_mode;
    }

    /**
     * Retourne l'icon
     *
     * @param [type] $filename
     * @return void
     */
    public function att_icon($filename)
    {
        global $att_icons, $att_icon_default,  $att_icon_multiple;

        $this->load_mimetypes();

        $suffix = strtoLower(substr(strrchr($filename, '.'), 1));

        return (isset($att_icons[$suffix])) ? $att_icons[$suffix] : $att_icon_default;
    }

    // Partie Graphique

    /**
     * Controle la taille de l image a afficher
     *
     * @param [type] $size
     * @return void
     */
    public function verifsize($size)
    {
        $width_max = 500;
        $height_max = 500;

        if ($size[0] == 0) {
            $size[0] = ceil($width_max / 3);
        }

        if ($size[1] == 0) {
            $size[1] = ceil($height_max / 3);
        }

        $width = $size[0];
        $height = $size[1];

        if ($width > $width_max) {
            $imageProp = ($width_max * 100) / $width;
            $height = ceil(($height * $imageProp) / 100);
            $width = $width_max;
        }

        if ($height > $height_max) {
            $imageProp = ($height_max * 100) / $height;
            $width = ceil(($width * $imageProp) / 100);
            $height = $height_max;
        }

        return ('width="' . $width . '" height="' . $height . '"');
    }

 

    /**
     * Fonction d'affichage des fichier text directement 
     * 
     * Copyright 1999 Dominic J. Eidson, use as you wish, but give credit
     * where credit due.
     *
     * @param [type] $string
     * @param integer $cols
     * @param string $prefix
     * @return void
     */
    public function word_wrap($string, $cols = 80, $prefix = '')
    {
        $t_lines = explode("\n", $string);
        $outlines = '';

        foreach ($t_lines as $thisline) {
            if (strlen($thisline) > $cols) {

                $newline = '';
                $t_l_lines = explode(' ', $thisline);

                foreach ($t_l_lines as $thisword) {
                    while ((strlen($thisword) + strlen($prefix)) > $cols) {
                        $cur_pos = 0;
                        $outlines .= $prefix;

                        for ($num = 0; $num < $cols - 1; $num++) {
                            $outlines .= $thisword[$num];
                            $cur_pos++;
                        }

                        $outlines .= "\n";
                        $thisword = substr($thisword, $cur_pos, (strlen($thisword) - $cur_pos));
                    }

                    if ((strlen($newline) + strlen($thisword)) > $cols) {
                        $outlines .= $prefix . $newline . "\n";
                        $newline = $thisword . ' ';
                    } else {
                        $newline .= $thisword . ' ';
                    }
                }
                $outlines .= $prefix . $newline . "\n";
            } else { 
                $outlines .= $prefix . $thisline . "\n";
            }
        }

        return $outlines;
    }

    /**
     * Affiche la source d une page html
     *
     * @param [type] $text
     * @return string
     */
    public function scr_html($text)
    {
        $text = str_replace('<', '&lt;', $text);
        $text = str_replace('>', '&gt;', $text);

        return $text;
    }

    /**
     * Effacer les fichier joint demander
     *
     * @param [type] $del_att
     * @return void
     */
    public function delete($del_att)
    {
        global $upload_table, $rep_upload_forum, $apli, $DOCUMENTROOT;

        $rep = $DOCUMENTROOT;
        
        if (is_array($del_att)) {

            $del_att = implode(',', $del_att);

            $sql = "SELECT att_id, att_name, att_path FROM $upload_table WHERE att_id IN ($del_att)";
            $result = sql_query($sql);

            while (list($att_id, $att_name, $att_path) = sql_fetch_row($result)) {
                @unlink($rep . "$att_path/$att_id.$apli.$att_name");
            }

            $sql = "DELETE FROM $upload_table WHERE att_id IN ($del_att)";
            sql_query($sql);
        }
    }

    /**
     * Update le type d affichage
     *
     * @param [type] $inline_att
     * @return void
     */
    public function update_inline($inline_att)
    {
        global $upload_table;

        if (is_array($inline_att)) {
            foreach ($inline_att as $id => $mode) {

                $sql = "UPDATE $upload_table SET inline='$mode' WHERE att_id=$id";
                sql_query($sql);
            }
        }
    }

    /**
     * Update la visibilité
     *
     * @param [type] $listeV
     * @param [type] $listeU
     * @return void
     */
    public function renomme_fichier($listeV, $listeU)
    {
        global $upload_table, $apli, $DOCUMENTROOT;

        $query = "SELECT att_id, att_name, att_path FROM $upload_table WHERE att_id in ($listeV) and visible=1";
        $result = sql_query($query);

        while ($attach = sql_fetch_assoc($result)) {
            if (!file_exists($DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.' . $attach['att_name'])) {
                rename($DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.@' . $attach['att_name'], $DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.' . $attach['att_name']);
            }
        }

        $query = "SELECT att_id, att_name, att_path FROM $upload_table WHERE att_id IN ($listeU) AND visible=0";
        $result = sql_query($query);

        while ($attach = sql_fetch_assoc($result)) {
            if (!file_exists($DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.@' . $attach['att_name'])) {
                rename($DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.' . $attach['att_name'], $DOCUMENTROOT . $attach['att_path'] . $attach['att_id'] . '.' . $apli . '.@' . $attach['att_name']);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $visible_att
     * @param [type] $visible_list
     * @return void
     */
    public function update_visibilite($visible_att, $visible_list)
    {
        global $upload_table;

        if (is_array($visible_att)) {
            $visible = implode($visible_att, ',');

            $sql = "UPDATE $upload_table SET visible='1' WHERE att_id IN ($visible)";
            sql_query($sql);

            $visible_lst = explode(',', substr($visible_list, 0, strlen($visible_list) - 1));
            $result = array_diff($visible_lst, $visible_att);
            $unvisible = implode(",", $result);

            $sql = "UPDATE $upload_table SET visible='0' WHERE att_id IN ($unvisible)";
            sql_query($sql);
        } else {
            $visible_lst = explode(',', substr($visible_list, 0, strlen($visible_list) - 1));
            $unvisible = implode(',', $visible_lst);

            $sql = "UPDATE $upload_table SET visible='0' WHERE att_id IN ($unvisible)";
            sql_query($sql);
        }
        
        $this->renomme_fichier($visible, $unvisible);
    }

}
