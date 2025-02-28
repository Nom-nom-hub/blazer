<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file exists, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . '/../../public' . $uri)) {
    return false;
}

// For file watch requests, use a 304 Not Modified response if file hasn't changed
if (isset($_GET['watch'])) {
    $file = __DIR__ . '/../../app/Views/welcome.php';
    $mtime = filemtime($file);
    $etag = '"' . md5($mtime) . '"';
    
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $etag) {
        header('HTTP/1.1 304 Not Modified');
        exit;
    }
    
    header('ETag: ' . $etag);
    die($mtime);
}

// Regular page request
ob_start();
require __DIR__ . '/../../public/index.php';
$content = ob_get_clean();

// Inject live reload script before </body>
if (strpos($content, '</body>') !== false) {
    $script = <<<'SCRIPT'
    <script>
        // Check for file changes every 2 seconds
        setInterval(() => {
            fetch('/?watch=1', { headers: { 'If-None-Match': window.lastEtag || '' } })
                .then(r => r.status === 304 ? null : r.text())
                .then(mtime => {
                    if (mtime) {
                        window.lastEtag = '"' + md5(mtime) + '"';
                        location.reload();
                    }
                });
        }, 2000);
    </script>
    </body>
    SCRIPT;
    
    $content = str_replace('</body>', $script, $content);
}

echo $content; 