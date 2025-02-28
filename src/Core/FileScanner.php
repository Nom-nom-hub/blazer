<?php

namespace Blazer\Core;

class FileScanner
{
    private static $instance = null;
    private $cache;
    private $scanDirs = ['app', 'src'];
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->cache = Cache::getInstance();
    }
    
    public function scan()
    {
        $cacheKey = 'file_structure';
        $cached = $this->cache->get($cacheKey);
        
        if ($cached) {
            return $cached;
        }
        
        $structure = [
            'namespaces' => [
                'App\\' => '/app',
                'Blazer\\' => '/src'
            ],
            'files' => [],
            'directories' => []
        ];
        
        foreach ($this->scanDirs as $dir) {
            if (!is_dir(ROOT_DIR . '/' . $dir)) {
                continue;
            }
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(ROOT_DIR . '/' . $dir)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $path = str_replace(ROOT_DIR, '', $file->getPathname());
                    $relativePath = trim($path, '/');
                    
                    // Store file info
                    $structure['files'][] = $relativePath;
                    
                    // Store directory info
                    $dir = dirname($relativePath);
                    if (!in_array($dir, $structure['directories'])) {
                        $structure['directories'][] = $dir;
                    }
                }
            }
        }
        
        $this->cache->set($cacheKey, $structure, 60);
        return $structure;
    }
    
    public function addScanDirectory($dir)
    {
        if (!in_array($dir, $this->scanDirs)) {
            $this->scanDirs[] = $dir;
            $this->cache->set('file_structure', null); // Invalidate cache
        }
    }
} 