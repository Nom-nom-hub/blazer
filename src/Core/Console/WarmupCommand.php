<?php

namespace Blazer\Core\Console;

class WarmupCommand
{
    public function run()
    {
        // Warm up opcache
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(ROOT_DIR)
        );
        
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                opcache_compile_file($file->getPathname());
            }
        }
        
        // Warm up route cache
        $router = new \Blazer\Core\Router();
        $router->compile();
        
        echo "Cache warmup complete!\n";
    }
} 