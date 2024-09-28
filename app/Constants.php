<?php

/**
 * [description]
 */
define('ENVIRONMENT', 'development');


/**
 * Set a default language.
 */
define('LANGUAGE_CODE', 'en');

/**
 * PREFER to be used in database calls default is Npds_
 */
define('DB_PREFIX', '');

/**
 * Set prefix for sessions.
 */
define('SESSION_PREFIX', 'Npds_');

/**
 * Optional create a constant for the name of the site.
 */
define('SITE_TITLE', 'Npds framework');

/**
 * Optional set a site email address.
 */
define('SITE_EMAIL', 'email@domain.com');

/**
 * Setup the Storage Path.
 */
define('STORAGE_PATH', BASEPATH .'storage'. DS);
