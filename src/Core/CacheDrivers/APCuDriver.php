<?php

namespace Blazer\Core\CacheDrivers;

class APCuDriver implements CacheDriverInterface
{
    public function get($key)
    {
        $success = false;
        $value = apcu_fetch($key, $success);
        return $success ? $value : null;
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        return apcu_store($key, $value, $ttl);
    }
} 