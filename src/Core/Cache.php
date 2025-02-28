<?php

namespace Blazer\Core;

use Blazer\Core\CacheDrivers\FileDriver;
use Blazer\Core\CacheDrivers\APCuDriver;

class Cache
{
    private static $instance = null;
    private $driver;
    
    private function __construct()
    {
        $this->driver = $this->getDriver();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function getDriver()
    {
        if (extension_loaded('apcu') && ini_get('apc.enabled')) {
            return new APCuDriver();
        }
        return new FileDriver();
    }
    
    public function get($key)
    {
        return $this->driver->get($key);
    }
    
    public function set($key, $value, $ttl = 3600)
    {
        return $this->driver->set($key, $value, $ttl);
    }
} 