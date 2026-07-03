<?php
// Built-in PHP server router: serve existing files directly, otherwise run index.php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Support local requests that include /public/ when the document root is already public/
if (strpos($uri, '/public/') === 0) {
    $uri = substr($uri, strlen('/public'));
}

$requested = __DIR__ . $uri;
if ($uri !== '/' && file_exists($requested)) {
    return false; // Serve the requested resource as-is.
}

require_once __DIR__ . '/index.php';
