<?php

// display mode if displayed inline

/**
 * displays as link (icon)
 */
if (!defined('ATT_DSP_LINK')) {
    define('ATT_DSP_LINK', '1');
}

/**
 * display inline as a picture, using <img> tag.
 */
if (!defined('ATT_DSP_IMG')) {
    define('ATT_DSP_IMG', '2');
}

/**
 * display inline as HTML, e.g. banned tags are stripped.
 */
if (!defined('ATT_DSP_HTML')) {
    define('ATT_DSP_HTML', '3'); 
}

/**
 * display inline as text, using <pre> tag.
 */
define('ATT_DSP_PLAINTEXT', '4'); 

/**
 * Embedded Macromedia Shockwave Flash
 */
define('ATT_DSP_SWF', '5');

// \Modules\Upload\Library\FileUpload

/**
 * 
 */
// define('_FILEUPLOAD', 1);

/**
 * 
 */
define('NO_FILE', -1);

/**
 * 
 */
define('FILE_TOO_BIG', -2);

/**
 * 
 */
define('INVALID_FILE_TYPE', -3);

/**
 * 
 */
define('DB_ERROR', -4);

/**
 * 
 */
define('COPY_ERROR', -5);

/**
 * 
 */
define('ERR_FILE', -6);

/**
 * 
 */
define('FILE_EMPTY', -7);

/**
 * 
 */
define('ERR_ARG', -8);

/**
 * 
 */
define('DEFAULT_INLINE', '1');

/**
 * 
 */
define('U_MASK', '0766');
