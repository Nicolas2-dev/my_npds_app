<?php





$mime_dspinl[$mimetype_default] = 'O';
$mime_dspfmt[$mimetype_default] = ATT_DSP_LINK;


// display mode if displayed inline
$mime_dspfmt['image/gif'] = ATT_DSP_IMG;
$mime_dspfmt['image/bmp'] = ATT_DSP_LINK;
$mime_dspfmt['image/png'] = ATT_DSP_IMG;
$mime_dspfmt['image/x-png'] = ATT_DSP_IMG;
$mime_dspfmt['image/jpeg'] = ATT_DSP_IMG;
$mime_dspfmt['image/pjpeg'] = ATT_DSP_IMG;
$mime_dspfmt['text/html'] = ATT_DSP_HTML;
$mime_dspfmt['text/plain'] = ATT_DSP_PLAINTEXT;
$mime_dspfmt['application/x-shockwave-flash'] = ATT_DSP_SWF;

// attachement

$mime_renderers[ATT_DSP_PLAINTEXT] = "<div class=\"list-group-item flex-column align-items-start\"><div class=\"py-2 mb-2\"><code>\$att_name\$visible_wrn</code></div><div style=\"width:100%; \"><pre>\$att_contents</pre></div></div>";

$mime_renderers[ATT_DSP_HTML]      = "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"background-color: #000000;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\"><tr><td align=\"center\" style=\"background-color: #cccccc;\">\$att_name\$visible_wrn</td></tr><tr><td style=\"background-color: #ffffff;\">\$att_contents</td></tr></table></td></tr></table>";
$mime_renderers[ATT_DSP_LINK]      = "
<a class=\"list-group-item d-flex justify-content-start align-items-center\" href=\"\$att_url\" target=\"_blank\" >
\$att_icon<span title=\"" . __d('upload', 'Télécharg.') . " \$att_name (\$att_type - \$att_size)\" data-bs-toggle=\"tooltip\" style=\"font-size: .85rem;\" class=\"ms-2 n-ellipses\"><strong>&nbsp;\$att_name</strong></span><span class=\"badge bg-secondary ms-auto\" style=\"font-size: .75rem;\">\$compteur &nbsp;<i class=\"fa fa-lg fa-download\"></i></span><br /><span align=\"center\">\$visible_wrn</span></a>";
$mime_renderers[ATT_DSP_IMG] = "<a class=\"list-group-item\" href=\"javascript:void(0);\" onclick=\"window.open('\$att_url','fullsizeimg','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=600,width=800,toolbar=no,scrollbars=yes,resizable=yes');\"><img src=\"\$att_url\" alt=\"\$att_name\" border=\"0\" \$img_size />\$visible_wrn </a>";
$mime_renderers[ATT_DSP_SWF] = "<p align=\"center\"><object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" \$img_size><param name=\"quality\" value=\"high\"><param name=\"SRC\" value=\"\$att_url\"><embed src=\"\$att_url\" quality=\"high\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" \$img_size></embed></object>\$visible_wrn</p>";




$att_icon_default = "<img src=\"assets/images/upload/file_types/unknown.gif\" border=\"0\" align=\"center\" alt=\"\" />";
$att_icon_multiple = "<img src=\"assets/images/upload/file_types/multiple.gif\" border=\"0\" align=\"center\" alt=\"\" />";
$att_icon_dir = "<img src=\"assets/images/upload/file_types/dir.gif\" border=\"0\" align=\"center\" alt=\"\" />";
