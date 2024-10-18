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
if (!defined('ATT_DSP_PLAINTEXT')) {
    define('ATT_DSP_PLAINTEXT', '4'); 
}

/**
 * Embedded Macromedia Shockwave Flash
 */
if (!defined('ATT_DSP_SWF')) {
    define('ATT_DSP_SWF', '5');
}

// \Modules\Upload\Library\FileUpload

/**
 * 
 */
if (!defined('FILEUPLOAD')) {
    define('FILEUPLOAD', 1);
}

/**
 * 
 */
if (!defined('NO_FILE')) {
    define('NO_FILE', -1);
}

/**
 * 
 */
if (!defined('FILE_TOO_BIG')) {
    define('FILE_TOO_BIG', -2);
}

/**
 * 
 */
if (!defined('INVALID_FILE_TYPE')) {
    define('INVALID_FILE_TYPE', -3);
}

/**
 * 
 */
if (!defined('DB_ERROR')) {
    define('DB_ERROR', -4);
}

/**
 * 
 */
if (!defined('COPY_ERROR')) {
    define('COPY_ERROR', -5);
}

/**
 * 
 */
if (!defined('ERR_FILE')) {
    define('ERR_FILE', -6);
}

/**
 * 
 */
if (!defined('FILE_EMPTY')) {
    define('FILE_EMPTY', -7);
}

/**
 * 
 */
if (!defined('ERR_ARG')) {
    define('ERR_ARG', -8);
}

/**
 * 
 */
if (!defined('DEFAULT_INLINE')) {
    define('DEFAULT_INLINE', '1');
}

/**
 * 
 */
if (!defined('U_MASK')) {
    define('U_MASK', '0766');
}
