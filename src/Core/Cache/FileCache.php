<?php

namespace Blazer\Core\Cache;

class FileCache {
    private $path;
    
    public function __construct($path = null) {
        $this->path = $path ?? ROOT_DIR . '/storage/cache';
    }
    
    public function set($key, $value, $ttl = 3600) {
        $file = $this->path . '/' . md5($key);
        $data = [
            'expires' => time() + $ttl,
            'value' => $value
        ];
        file_put_contents($file, serialize($data));
    }
    
    public function get($key) {
        $file = $this->path . '/' . md5($key);
        if (file_exists($file)) {
            $data = unserialize(file_get_contents($file));
            if ($data['expires'] > time()) {
                return $data['value'];
            }
            unlink($file);
        }
        return null;
    }
} 