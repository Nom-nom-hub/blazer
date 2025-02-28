<?php

// Silence all output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Minimal logging for watch requests
if (isset($_GET['watch'])) {
    header('Content-Type: application/json');
    require_once __DIR__ . '/index.php';
    exit;
}

// Handle static files silently
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route everything through index.php silently
require_once __DIR__ . '/index.php'; 