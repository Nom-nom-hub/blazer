<?php

namespace Blazer\Core\CacheDrivers;

interface CacheDriverInterface
{
    public function get($key);
    public function set($key, $value, $ttl = 3600);
} 