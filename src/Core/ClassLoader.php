<?php

namespace Blazer\Core;

class ClassLoader
{
    private static $loaded = [];
    private static $namespaces = [
        'App\\' => '/app',
        'Blazer\\' => '/src'
    ];
    private static $loadOrder = [];
    
    public static function register()
    {
        spl_autoload_register([self::class, 'loadClass'], true, true);
    }
    
    public static function loadClass($class)
    {
        if (isset(self::$loaded[$class])) {
            return true;
        }
        
        $prefix = substr($class, 0, strpos($class, '\\') + 1);
        if (!isset(self::$namespaces[$prefix])) {
            return false;
        }
        
        $file = ROOT_DIR . self::$namespaces[$prefix] . '/' . 
            str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
            
        if (file_exists($file)) {
            require_once $file;
            self::$loaded[$class] = true;
            self::$loadOrder[] = $file;
            return true;
        }
        
        return false;
    }
    
    public static function getLoadedFiles()
    {
        return self::$loadOrder;
    }
    
    private static function saveClassMap()
    {
        $file = ROOT_DIR . '/storage/cache/classmap.php';
        $content = '<?php return ' . var_export(self::$classMap, true) . ';';
        file_put_contents($file, $content);
        if (function_exists('opcache_compile_file')) {
            opcache_compile_file($file);
        }
    }

    private static function shouldLoad($class)
    {
        // Skip DevServer in non-CLI mode
        if ($class === 'Blazer\Core\DevServer' && PHP_SAPI !== 'cli') {
            return false;
        }
        return true;
    }
} 