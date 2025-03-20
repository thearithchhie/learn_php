<?php
// server.php - For PHP built-in server

// This simulates the front controller pattern for the built-in PHP server
if (php_sapi_name() === 'cli-server') {
    // Static file check: if the file exists, serve it directly
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . '/public' . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

// Otherwise, load the index.php to handle the request
require_once __DIR__ . '/public/index.php';