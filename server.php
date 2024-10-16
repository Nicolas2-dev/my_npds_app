<?php

// This file allows us to emulate Apache's "mod_rewrite" functionality from the built-in PHP web server.
// Provides a convenient way to test the application without having installed a "real" web server software.

// Usage:
// php -S localhost:8002 -t public/ server.php


$publicPath = 'public/';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = urldecode($uri);

$requested = $publicPath .$uri;

if (($uri !== '/') && file_exists($requested)) {
    return false;
}

require_once $publicPath .'index.php';