<?php

namespace Blazer\Core;

class DevServer
{
    private $watchDirs = ['app', 'config', 'src'];
    private static $lastCheck = null;
    private static $fileHashes = [];
    
    public function watch()
    {
        echo <<<HTML
        <script>
        let lastCheck = 0;
        let isChecking = false;
        
        async function checkForChanges() {
            if (isChecking) return;
            
            try {
                isChecking = true;
                const response = await fetch('/?watch=1&t=' + Date.now(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                console.log('[Blazer] Check result:', data);
                
                if (data.reload) {
                    console.log('[Blazer] Changes detected, reloading...');
                    window.location.reload();
                }
                
                lastCheck = data.timestamp;
            } catch (e) {
                console.error('[Blazer] Watch error:', e);
            } finally {
                isChecking = false;
            }
        }
        
        // Initial check
        checkForChanges();
        
        // Check every 1 second
        setInterval(checkForChanges, 1000);
        </script>
        HTML;
    }
    
    public function checkChanges()
    {
        $currentTime = microtime(true);
        $result = ['reload' => false, 'timestamp' => $currentTime];
        
        // Only check every 1 second
        if (self::$lastCheck && ($currentTime - self::$lastCheck) < 1) {
            return $result;
        }
        
        foreach ($this->watchDirs as $dir) {
            $path = ROOT_DIR . '/' . $dir;
            if (!is_dir($path)) continue;
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $path = $file->getPathname();
                    $mtime = $file->getMTime();
                    
                    if (!isset(self::$fileHashes[$path]) || self::$fileHashes[$path] !== $mtime) {
                        self::$fileHashes[$path] = $mtime;
                        $result['reload'] = true;
                        $result['changed'] = $path;
                        break;
                    }
                }
            }
            
            if ($result['reload']) break;
        }
        
        self::$lastCheck = $currentTime;
        return $result;
    }
} 