<?php
/**
 * Blazer Framework - Front Controller
 *
 * This file serves as the entry point for all requests.
 * It bootstraps the application, loads configuration, processes
 * the request, and outputs a response.
 */

// Start session for file watching
session_start();

// Define the application root directory
define('ROOT_DIR', dirname(__DIR__));
define('START_TIME', microtime(true));

// Load environment variables
if (file_exists(ROOT_DIR . '/.env')) {
    $lines = file(ROOT_DIR . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Enable opcache
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Set realpath cache
ini_set('realpath_cache_size', '4096K');
ini_set('realpath_cache_ttl', '600');

// Debug file loading
$loadedFiles = get_included_files();
$initialCount = count($loadedFiles);

// Required core files
require_once ROOT_DIR . '/src/Core/Cache.php';
require_once ROOT_DIR . '/src/Core/ClassLoader.php';

// Register class loader
Blazer\Core\ClassLoader::register();

// Handle file watch requests
if (isset($_GET['watch'])) {
    header('Content-Type: application/json');
    
    $lastHash = $_GET['hash'] ?? '';
    $files = [
        '/app/Views/welcome.php',
        '/app/Controllers/WelcomeController.php',
        '/config/routes.php'
    ];
    
    $currentHash = '';
    $changedFile = null;
    
    // Build current state hash
    foreach ($files as $file) {
        $path = ROOT_DIR . $file;
        if (file_exists($path)) {
            $mtime = filemtime($path);
            $currentHash .= $file . ':' . $mtime . ';';
        }
    }
    
    // Only check for changes if we have a previous hash
    if ($lastHash && $lastHash !== md5($currentHash)) {
        // Find which file changed by comparing individual hashes
        foreach ($files as $file) {
            $path = ROOT_DIR . $file;
            if (file_exists($path)) {
                $fileHash = $file . ':' . filemtime($path);
                if (strpos($lastHash, md5($fileHash)) === false) {
                    $changedFile = $file;
                    error_log("[Blazer] File changed: " . $file);
                    break;
                }
            }
        }
    }
    
    $currentHash = md5($currentHash);
    
    echo json_encode([
        'reload' => $changedFile !== null,
        'hash' => $currentHash,
        'file' => $changedFile
    ]);
    exit;
}

// Initialize router
$router = new Blazer\Core\Router();

try {
    $response = $router->dispatch();
    $response->send();
} catch (Exception $e) {
    $message = $_ENV['APP_DEBUG'] ? $e->getMessage() : 'Server Error';
    (new Blazer\Core\Response($message, 500))->send();
}

// Add after error reporting
if (function_exists('opcache_compile_file')) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(ROOT_DIR . '/app')
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            opcache_compile_file($file->getPathname());
        }
    }
} 