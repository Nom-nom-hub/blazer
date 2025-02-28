<?php
if (isset($_GET['watch'])) {
    clearstatcache(true);
    
    // Watch both app and src directories
    $patterns = [
        __DIR__ . '/../../app/**/*.php',
        __DIR__ . '/../../src/**/*.php',
        __DIR__ . '/../../app/Views/**/*.php',
        __DIR__ . '/../../resources/views/**/*.php'
    ];
    
    $files = [];
    foreach ($patterns as $pattern) {
        $files = array_merge($files, glob($pattern, GLOB_BRACE));
    }
    
    $changes = [];
    $timestamp = $_GET['since'] ?? 0;
    $newTimestamp = $timestamp;
    
    foreach ($files as $file) {
        $mtime = filemtime($file);
        if ($mtime > $timestamp) {
            $relPath = str_replace(__DIR__ . '/../../', '', $file);
            $changes[] = [
                'file' => $relPath,
                'time' => $mtime
            ];
            if ($mtime > $newTimestamp) {
                $newTimestamp = $mtime;
            }
            
            // Log to both terminal and file
            $time = date('H:i:s', $mtime);
            $message = "[$time] File changed: $relPath\n";
            echo $message; // Print to terminal
            file_put_contents(
                __DIR__ . '/../../storage/logs/dev-server.log',
                $message,
                FILE_APPEND
            );
        }
    }
    
    header('Content-Type: application/json');
    die(json_encode([
        'timestamp' => $newTimestamp,
        'changes' => $changes
    ]));
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/../../public' . $uri;

if ($uri !== '/' && file_exists($file)) {
    return false;
}

ob_start();
require __DIR__ . '/../../public/index.php';
$content = ob_get_clean();

if (strpos($content, '</body>') !== false) {
    $script = <<<'JS'
    <script>
        let lastTimestamp = Math.floor(Date.now() / 1000); // Start with current timestamp
        let checkTimeout;
        
        const checkForChanges = async () => {
            try {
                const response = await fetch(`/?watch=1&since=${lastTimestamp}`);
                const data = await response.json();
                
                if (data.changes.length > 0) {
                    console.group('Files changed:');
                    data.changes.forEach(change => {
                        const time = new Date(change.time * 1000).toLocaleTimeString();
                        console.log(`%c[${time}] ${change.file}`, 'color: #4CAF50; font-weight: bold');
                    });
                    console.groupEnd();
                    window.location.reload();
                }
                
                // Only update timestamp if we got a valid response
                if (data.timestamp > lastTimestamp) {
                    lastTimestamp = data.timestamp;
                }
            } catch (err) {
                console.error("Hot reload check failed:", err);
            }
            
            // Check every 2 seconds
            checkTimeout = setTimeout(checkForChanges, 2000);
        };
        
        // Start checking when page is visible
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                clearTimeout(checkTimeout);
            } else {
                checkForChanges();
            }
        });
        
        // Initial check
        checkForChanges();
        
        // Show startup message
        console.log('%c🔥 Hot reload enabled', 'color: #4CAF50; font-weight: bold');
    </script>
    </body>
JS;
    
    $content = str_replace('</body>', $script, $content);
}

echo $content;