<?php

namespace Modules\Upload\Support\Traits;

use Npds\Http\Request;
use Npds\Config\Config;
use Modules\Npds\Support\Error;
use Npds\Filesystem\FileManagement;
use Modules\Npds\Support\Facades\Auth;
use Modules\Users\Support\Facades\User;
use Modules\Npds\Support\Facades\Language;
use Modules\Upload\Support\UploadHtmlListBox;
use Modules\Upload\Support\Facades\NpdsUpload;
use Modules\Upload\Support\UploadHtmlCheckBox;

/**
 * Undocumented trait
 */
trait UploadMinegfTrait
{

    /**
     * Undocumented function
     *
     * @param [type] $IdPost
     * @param [type] $IdTopic
     * @param [type] $IdForum
     * @param [type] $apli
     * @param [type] $thanks_msg
     * @return void
     */
    private string $thanks_msg;

    /**
     * 
     */
    private string $moderator;


    /**
     * Undocumented function
     *
     * @return void
     */
    public function minigf()
    {
        $IdPost     = Request::post('IdPost');
        $IdTopic    = Request::post('IdTopic');
        $IdForum    = Request::post('IdForum');
        $apli       = Request::post('apli');

        $this->displayJava();

        echo '
            <form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data" name="form0" onsubmit="return checkForm(this);" lang="' . Language::language_iso(1, '', '') . '">
                <input type="hidden" name="actiontype" value="" />
                <input type="hidden" name="IdPost" value="' . $IdPost . '" />
                <input type="hidden" name="IdForum" value="' . $IdForum . '" />
                <input type="hidden" name="IdTopic" value="' . $IdTopic . '" />
                <input type="hidden" name="apli" value="' . $apli . '" />';
        
        // moderator    
        $Mmod = $this->get_mod();

        $att = NpdsUpload::getAttachments($apli, $IdPost, 0, $Mmod);
        
        if (is_array($att)) {

            $att_table = '
                        <table data-toggle="table" data-classes="table table-sm table-no-bordered table-hover table-striped" data-mobile-responsive="true">
                        <thead>
                            <tr>
                                <th class="n-t-col-xs-1"><i class="fas fa-trash fa-lg text-danger"></i></th>
                                <th class="n-t-col-xs-3" data-halign="center" data-align="center" data-sortable="true">' . __d('upload', 'Fichier') . '</th>
                                <th data-halign="center" data-align="center" data-sortable="true">' . __d('upload', 'Type') . '</th>
                                <th data-halign="center" data-align="right">' . __d('upload', 'Taille') . '</th>
                                <th data-halign="center" data-align="center">' . __d('upload', 'Affichage intégré') . '</th>';
                               
            if ($Mmod) {
                $att_table .= '<th data-halign="center" data-align="center">' . __d('upload', 'Visibilité') . '</th>';
            }

            $att_table .= '</tr>
                        </thead>
                        <tbody>';
        
            // essai class PHP7            
            $Fichier        = new FileManagement; 
            $visu           = '';
            $visible_list   = '';

            $att_count  = count($att);
            $tsz        = 0;

            for ($i = 0; $i < $att_count; $i++) {
                $id = $att[$i]['att_id'];
                $tsz += $att[$i]['att_size'];
        
                if (NpdsUpload::getAttDisplayMode($att[$i]['att_type'], 'A') == ATT_DSP_LINK) {
                    // This mime-type can't be displayed inline
                    echo '<input type="hidden" name="inline_att[' . $id . ']" value="0" />';
                    
                    $inline_box = '--';
                } else {
                    $inline_box = with( new UploadHtmlListBox('inline_att['. $id .']', $att[$i]['inline']))->display();
                }
        
                if ($Mmod) {
                    $is_visible = (($att[$i]['visible'] == 1) ? $id : -1);
                    $visu = '<td>' . with( new UploadHtmlCheckBox('visible_att[]', $id, $is_visible, ''))->display() . '</td>';

                    $visible_list .= $id . ',';
                }
        
                $att_table .= '
                            <tr>
                                <td>' . NpdsUpload::getCheckBox("del_att[]", $id, 0, '') . '</td>
                                <td>' . $att[$i]['att_name'] . '</td>
                                <td>' . $att[$i]['att_type'] . '</td>
                                <td>' . $Fichier->file_size_format($att[$i]['att_size'], 2) . '</td>
                                <td>' . $inline_box . '</td>
                                ' . $visu . '
                            </tr>';
            }
        
            $visu_button = '';
        
            echo '<input type="hidden" name="visible_list" value="' . $visible_list . '">';

            $att_inline_button = '
                <button class="btn btn-outline-primary btn-sm btn-block" onclick="InlineType(this.form);">
                    ' . __d('upload', 'Adapter') . '<span class="d-none d-xl-inline"> ' . __d('upload', 'Affichage intégré') . '</span>
                </button>';
        
            if ($Mmod) {
                $visu_button = '
                <button class="btn btn-outline-primary btn-sm btn-block" onclick="visibleFile(this.form);">
                    ' . __d('upload', 'Adapter') . '<span class="d-none d-xl-inline"> ' . __d('upload', 'Visibilité') . '</span>
                </button>';
            }
        
            $att_table .= '
                        </tbody>
                        </table>
                        <div class="row p-2">
                            <div class="col-sm-4 col-6 mb-2">
                                <i class="fas fa-level-up-alt fa-2x fa-flip-horizontal text-danger me-1"></i>
                                <a class="text-danger" href="#" onclick="deleteFile(document.form0); return false;">
                                    <span class="d-sm-none" title="' . __d('upload', 'Supprimer les fichiers sélectionnés') . '" data-bs-toggle="tooltip" data-bs-placement="right" >
                                        <i class="fas fa-trash fa-2x ms-1"></i>
                                    </span>
                                    <span class="d-none d-sm-inline">' . __d('upload', 'Supprimer les fichiers sélectionnés') . '</span>
                                </a>
                            </div>
                            <div class="col-sm-4 text-end col-6 mb-2">
                                <strong>' . __d('upload', 'Total :') . ' ' . $Fichier->file_size_format($tsz, 1) . '</strong>
                            </div>
                            <div class="col-sm-2 text-center-sm mb-2 col-12 ">
                                ' . $att_inline_button . '
                            </div>
                            <div class="col-sm-2 text-center-sm mb-2 col-12">
                                ' . $visu_button . '
                            </div>
                        </div>';
        }
        
        $tf = new FileManagement;

        $att_upload_table = '
            <div class="card card-body my-2">
                <div class="mb-3 row">
                    <label class="col-form-label col-sm-3" for="pcfile">' . __d('upload', 'Fichier joint') . '</label>
                    <div class="col-sm-9">
                        <div class="input-group mb-2 me-sm-2">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="pcfile" id="pcfile" onchange="confirmSendFile(this.form);"/>
                            <label id="lab" class="custom-file-label" for="pcfile">' . __d('upload', 'Sélectionner votre fichier') . '</label>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-9 ms-sm-auto">
                        <button type="button" class="btn btn-primary" onclick="uploadFile(this.form);">' . __d('upload', 'Joindre') . '</button>
                    </div>
                </div>
                <p class="mb-0">' . __d('upload', 'Taille maxi du fichier') . ' : ' . $tf->file_size_format(Config::get('upload.forum.max_file_size'), 1) . '</p>
                <p class="mb-0">' . __d('upload', 'Extensions autorisées') . ' : <small class="text-success">' . Config::get('upload.forum.bn_allowed_extensions') . '</small></p>
            </div>';
        
        $att_form = '
            <div class="container-fluid p-3">
                <div class="text-end">
                    <span class="btn btn-outline-secondary btn-sm" onclick="self.close()">&times;</span>
                </div>
                ' . $this->get_thanks_msg();
        
        $att_form .= $att_upload_table . $att_table;
        
        echo $att_form . '
                    </div>
                    </form>
                </body>
            </html>';
        
        ob_end_flush();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function forum_moderateur()
    {
        // Moderator
        $forum = Request::query('IdForum');

        $sql = "SELECT forum_moderator FROM forums WHERE forum_id = '$forum'";
        if (!$result = sql_query($sql)) {
            Error::code('0001');
        }

        $myrow      = sql_fetch_assoc($result);
        $moderator  = User::get_moderator($myrow['forum_moderator']);
        $moderator  = explode(' ', $moderator);

        $Mmod = false;

        $user = Auth::check('user');

        $userX      = base64_decode($user);
        $userdata   = explode(':', $userX);

        for ($i = 0; $i < count($moderator); $i++) {
            if (($userdata[1] == $moderator[$i])) {
                $Mmod = true;
                break;
            }
        }

        return $this->set_mod($Mmod);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function get_mod()
    {
        return $this->moderator;
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @return void
     */
    private function set_mod($Mmod)
    {
        return $this->moderator = $Mmod;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function get_thanks_msg()
    {
        return (!empty($this->thanks_msg) ?: '');
    }

    /**
     * Undocumented function
     *
     * @param [type] $message
     * @return void
     */
    private function set_thanks_msg($message)
    {
        return $this->thanks_msg = $message;
    }

    /**
     * NOTE : Code a metre dans une vue
     * Undocumented function
     *
     * @return void
     */
    private function displayJava()
    {
        ?>
        <script type="text/javascript" src="<?= site_url('assets/shared/jquery/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/shared/bootstrap/dist/js/bootstrap.bundle.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/shared/bootstrap-table/dist/bootstrap-table.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/shared/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/shared/bootboxjs/bootbox.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= site_url('assets/js/npds_adapt.js'); ?>"></script>

        <script type="text/javascript">
            //<![CDATA[
            function htmlDecode(value) {
                return $("<textarea/>").html(value).text();
            }

            function htmlEncode(value) {
                return $('<textarea/>').text(value).html();
            }

            var has_submitted = 0;

            function checkForm(f) {
                if (has_submitted == 0) {
                    sel = false;

                    for (i = 0; i < f.elements.length; i++) {
                        if ((f.elements[i].name == 'del_att[]') && (f.elements[i].checked)) {
                            sel = true;
                            break;
                        }
                    }

                    if (sel) {
                        if (window.confirm(htmlDecode('"<?= __d('upload', 'Supprimer les fichiers sélectionnés ?'); ?>"'))) {
                            has_submitted = 1;

                            setTimeout('has_submitted=0', 5000);
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        has_submitted = 1;
                        setTimeout('has_submitted=0', 5000);

                        return true;
                    }
                } else {
                    bootbox.alert(htmlDecode("<?= __d('upload', 'Cette page a déjà été envoyée, veuillez patienter'); ?>"));

                    return false;
                }
            }

            function uniqueSubmit(f) {
                if (has_submitted == 0) {
                    has_submitted = 1;
                    setTimeout('has_submitted=0', 5000);

                    f.submit();
                } else {
                    bootbox.alert(htmlDecode("<?= __d('upload', 'Cette page a déjà été envoyée, veuillez patienter'); ?>"));

                    return false;
                }
            }

            function deleteFile(f) {
                sel = false;
                for (i = 0; i < f.elements.length; i++) {
                    if ((f.elements[i].name == 'del_att[]') && (f.elements[i].checked)) {
                        sel = true;
                        break;
                    }
                }

                if (sel == false) {
                    f.actiontype.value = '';
                    bootbox.alert(htmlDecode("<?= __d('upload', 'Vous devez tout d\'abord choisir la Pièce jointe à supprimer'); ?>"));

                    return false;
                } else {
                    bootbox.confirm(htmlDecode("<?= __d('upload', 'Supprimer les fichiers sélectionnés ?'); ?>"), function(result) {
                        if (result === true) {
                            f.actiontype.value = 'delete';

                            uniqueSubmit(f);
                            return true;
                        } else
                            return false;
                    });
                }
            }

            function visibleFile(f) {
                f.actiontype.value = 'visible';
                f.submit();
            }

            function InlineType(f) {
                f.actiontype.value = 'update';
                uniqueSubmit(f);
            }

            function uploadFile(f) {
                if (f.pcfile.value.length > 0) {
                    f.actiontype.value = 'upload';
                    uniqueSubmit(f);
                } else {
                    f.actiontype.value = '';
                    bootbox.alert(htmlDecode("<?= __d('upload', 'Vous devez sélectionner un fichier'); ?>"));
                    f.pcfile.focus();
                }
            }

            function confirmSendFile(f) {
                bootbox.confirm("<?= __d('upload', 'Joindre le fichier maintenant ?'); ?>",
                    function(result) {
                        if (result === true) {
                            uploadFile(f);
                            return true;
                        }
                    });
            }

            //]]>
        </script>
        <?php
    }

}
