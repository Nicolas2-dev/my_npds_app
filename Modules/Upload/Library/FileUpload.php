<?php

namespace Modules\Upload\Library;

use Modules\Upload\Support\Facades\NpdsUpload;

/**
 * Undocumented class
 */
class FileUpload
{

    /**
     * Undocumented variable
     *
     * @var integer
     */
    public $errno = 0;

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $upload_dir = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $IdForum = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $apli = '';

    /**
     * Undocumented variable
     *
     * @var string
     */
    public $Halt_On_Error = 'report';


    /**
     * Constructor : Initialize some variables
     * @param     string $dir   directory into which save the attached files
     * @access    public
     * @return    void
     */
    public function init($dir, $forum, $apli)
    {
        $this->upload_dir   = $dir;
        $this->IdForum      = $forum;
        $this->apli         = $apli;
    }

    /**
     * error handling
     * @param
     * @access    private
     * @return    void
     */
    public function halt($msg = '')
    {
        if ($this->Halt_On_Error == 'no') {
            return;
        }

        switch ($this->errno) {
            case FILE_TOO_BIG:
                $reason = __d('upload', 'La taille de ce fichier excède la taille maximum autorisée') . ' !</div>';
                break;

            case INVALID_FILE_TYPE:
                $reason = __d('upload', 'Ce type de fichier n\'est pas autorisé') . ' !</div>';
                break;

            default;
                $reason = sprintf(__d('upload', 'Le code erreur est : %s'), $this->errno);
                break;
        }

        /*je ne trouve pas quand et ou cette variable défini ci dessus peut etre changé donc ne comprend pas les conditions ci dessous ?*/
        if ($this->Halt_On_Error == 'report') {
            printf('<div class="alert alert-danger m-3 alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <h4 class="alert-heading">' . __d('upload', 'Attention') . '</h4> %s
                <br /><p class="mt-2 text-center"> %s </p>',
                 $msg, '<strong>' . $reason . '</strong>');
        } else {
            printf('<div class="alert alert-danger m-3" role="alert">
                 %s %s<br /><p class="mt-2 text-center"> %s </p></span>',
                 '<h4 class="alert-heading">File management</h4>',
                 $msg, '<strong>' . $reason . '</strong>');
        }

        if ($this->Halt_On_Error != 'report') {
            die('<div class="alert alert-danger m-3" role="alert">' . __d('upload', 'Session terminée.') . '</div>');
        }
    }

    /**
     * Copy one uploaded file to his destination and insert an entry in the database
     * @access    private
     * @return    boolean   TRUE if OK
     */
    public function uploadFile($IdPost, $IdTopic, $name, $size, $type, $src_file, $inline = DEFAULT_INLINE)
    {
        global $MAX_FILE_SIZE, $mimetypes, $mimetype_default, $insert_base;

        settype($size, 'integer');

        $this->errno = 0;

        //  Check temporary file
        if (empty($src_file) || (strcasecmp($src_file, 'none') == 0)) {
            $this->errno = NO_FILE;

            return false;
        }

        // Check size
        if ($size == 0) {
            $this->errno = FILE_EMPTY;

            return false;
        } else {
            $fsize = filesize($src_file);
        }

        if ($size != $fsize) {
            $this->errno = ERR_FILE;

            return false;
        }

        if ($size > $MAX_FILE_SIZE) {
            $this->errno = FILE_TOO_BIG;

            return false;
        }

        //  Check name
        if (empty($name)) {
            $this->errno = NO_FILE;

            return false;
        }

        $name = preg_replace('#[/\\\:\*\?"<>|]#i', '_', rawurldecode($name));

        // Check type and extension
        NpdsUpload::load_mimetypes();

        $suffix = strtoLower(substr(strrchr($name, '.'), 1));

        if (isset($mimetypes[$suffix])) {
            $type = $mimetypes[$suffix];

        } elseif (empty($type) || ($type == 'application/octet-stream')) {
            $type = $mimetype_default;
        }

        if (! $this->isAllowedFile($name, $type)) {
            $this->errno = INVALID_FILE_TYPE;
            return FALSE;
        }

        // Find the path to upload directory
        global $DOCUMENTROOT;

        $rep = $DOCUMENTROOT;

        settype($log_filename, "string");

        if ($insert_base == true) {
            // insert attachment reference in database
            $id = NpdsUpload::insertAttachment($this->apli, $IdPost, $IdTopic, $this->IdForum, $name, $this->upload_dir, $inline, $size, $type);

            if ($id <= 0) {
                $this->errno = DB_ERROR;
                return FALSE;
            }

            // copy temporary file to the upload directory
            $dest_file  = $rep . $this->upload_dir . "$id." . $this->apli . ".$name";
            $copyfunc   = (function_exists('move_uploaded_file')) ? 'move_uploaded_file' : 'copy';

            if (! $copyfunc($src_file, $dest_file)) {
                NpdsUpload::deleteAttachment($this->apli, $IdPost, $rep . $this->upload_dir, $id, $name);
                $this->errno = COPY_ERROR;

                return false;
            }

            @chmod($dest_file, 0766);
            $log_filename = $dest_file;
        } else {
            if ($this->apli == "minisite") {
                // copy temporary file to the upload directory
                global $rep_upload_minisite;

                $copyfunc = (function_exists('move_uploaded_file')) ? 'move_uploaded_file' : 'copy';

                if (! $copyfunc($src_file, $rep . $rep_upload_minisite . $name)) {
                    $this->errno = COPY_ERROR;

                    return false;
                }

                @chmod($rep . $rep_upload_minisite . $name, 0766);
                $log_filename = $rep . $rep_upload_minisite . $name;

            } elseif ($this->apli == "editeur") {
                // copy temporary file to the upload directory
                global $rep_upload_editeur;

                $copyfunc = (function_exists('move_uploaded_file')) ? 'move_uploaded_file' : 'copy';

                if (! $copyfunc($src_file, $rep . $rep_upload_editeur . $name)) {
                    $this->errno = COPY_ERROR;

                    return false;
                }

                @chmod($rep . $rep_upload_editeur . $name, 0766);
                $log_filename = $rep . $rep_upload_editeur . $name;
            } else {
                return false;
            }
        }

        Ecr_Log('security', 'Upload File(s) : ' . getip(), $log_filename);

        return true;
    }

    /**
     * Get files uploaded
     * @access    public
     * @return    array
     */
    public function getUploadedFiles($IdPost, $IdTopic)
    {
        global $pcfile, $pcfile_size, $pcfile_name, $pcfile_type;
        
        $this->errno = 0;

        $att_size   = 0;
        $att_count  = 0;

        if (is_string($pcfile) && !empty($pcfile) && !empty($pcfile_name)) {
            if ($pcfile == 'none') {
                $errmsg = sprintf(__d('upload', 'Erreur de téléchargement du fichier %s (%s) - Le fichier n\'a pas été sauvé'), $pcfile_name, $pcfile_type);
                
                $this->errno = NO_FILE;
                $this->halt($errmsg);

            } elseif ($this->uploadFile($IdPost, $IdTopic, $pcfile_name, $pcfile_size, $pcfile_type, $pcfile, DEFAULT_INLINE)) {
                $att_size = $pcfile_size;
                $att_count = 1;

            } else {
                $errmsg = sprintf(__d('upload', 'Erreur de téléchargement du fichier %s (%s) - Le fichier n\'a pas été sauvé'), $pcfile_name, $pcfile_type);
                $this->halt($errmsg);
            }

        } elseif (is_array($pcfile)) {
            $nfiles = count($pcfile);

            for ($i = 0; $i < $nfiles; $i++) {
                if (!empty($pcfile[$i]) && (strtolower($pcfile[$i]) != 'none')) {

                    if ($this->uploadFile($IdPost, $IdTopic, $pcfile_name[$i], $pcfile_size[$i], $pcfile_type[$i], $pcfile[$i], DEFAULT_INLINE)) {
                        $att_size += $pcfile_size[$i];
                        $att_count++;

                    } else {
                        $errmsg = sprintf(__d('upload', 'Erreur de téléchargement du fichier %s (%s) - Le fichier n\'a pas été sauvé'), $pcfile_name[$i], $pcfile_type[$i]);
                        $this->halt($errmsg);
                    }
                }
            }
        } else {
            $this->errno = NO_FILE;

            return false;
        }

        if ($att_size > 0) {
            $att['att_size'] = $att_size;
            $att['att_count'] = $att_count;
            
            return $att;
        } else {
            return false;
        }
    }

    /**
     * Check if the file is allowed for upload
     * Check if either the extension or the file type (mime-type) is allowed in this
     * configuration
     * @param   string  $filename     the name of the file
     * @param   string  $mimetype     the mime type
     * @access    public
     * @return    void
     * @throws
     */
    public function isAllowedFile($filename, $mimetype)
    {
        global $bn_allowed_extensions, $bn_allowed_mimetypes, $bn_banned_extensions, $bn_banned_mimetypes;

        // First check allowed extensions
        $ext = strtolower(strrchr($filename, '.'));

        if (!empty($bn_allowed_extensions)) {
            $allowed_extensions = explode(' ', $bn_allowed_extensions);

            if (is_array($allowed_extensions)) {
                $found = false;

                foreach ($allowed_extensions as $goodext) {
                    if ($ext == $goodext) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return false;
                }
            }
        }

        // Now deny banned extension
        if (!empty($bn_banned_extensions)) {
            $banned_extensions = explode(' ', $bn_banned_extensions);

            if (is_array($banned_extensions)) {
                foreach ($banned_extensions as $badext) {
                    if ($ext == $badext) {
                        return false;
                    }
                }
            }
        }

        // Now check mime-type
        list($type, $subtype) = explode('/', $mimetype);

        // check allowed mime-types
        if (!empty($bn_allowed_mimetypes)) {
            $allowed_mimetypes = explode(' ', $bn_allowed_mimetypes);

            if (is_array($allowed_mimetypes)) {
                $found = false;

                foreach ($allowed_mimetypes as $mt) {
                    list($good_type, $good_subtype) = explode('/', $mt);

                    if ($type == $good_type) {
                        if (($good_subtype == '*') || ($subtype == $good_subtype)) {
                            $found = true;
                            break;
                        }
                    }
                }

                if (!$found) {
                    return false;
                }
            }
        }

        // check denied mime-types
        if (!empty($bn_banned_mimetypes)) {
            $banned_mimetypes = explode(' ', $bn_banned_mimetypes);

            if (is_array($banned_mimetypes)) {
                foreach ($banned_mimetypes as $mt) {
                    list($bad_type, $bad_subtype) = explode('/', $mt);

                    if ($type == $bad_type) {
                        if (($bad_subtype == '*') || ($subtype == $bad_subtype)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

}
