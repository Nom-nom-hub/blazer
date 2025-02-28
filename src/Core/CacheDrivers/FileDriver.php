<?php

namespace Blazer\Core\CacheDrivers;

class FileDriver implements CacheDriverInterface
{
    private $path;
    
    public function __construct()
    {
        $this->path = ROOT_DIR . '/storage/cache';
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    public function get($key)
    {
        $file = $this->path . '/' . md5($key);
        if (!file_exists($file)) {
            return null;
        }
        
        $data = unserialize(file_get_contents($file));
        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }
        
        return $data['value'];
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        $file = $this->path . '/' . md5($key);
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        return file_put_contents($file, serialize($data));
    }
} 