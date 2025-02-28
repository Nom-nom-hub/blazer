<?php

namespace Blazer\Core;

use Blazer\Core\Request;
use Blazer\Core\Response;
use RuntimeException;

class Router
{
    /**
     * Collection of registered routes
     *
     * @var array
     */
    private static $routes = [];

    /**
     * Collection of compiled routes
     *
     * @var array
     */
    private $compiledRoutes = [];

    /**
     * Debug mode
     *
     * @var bool
     */
    private $debug;

    private static $routesLoaded = false;

    private $request;
    private $cache;
    private $cacheFile = '/storage/cache/routes.cache';

    public function __construct()
    {
        $this->request = new Request();
        $this->cache = Cache::getInstance();
        $this->debug = isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'];
        
        // Only load routes once per process
        if (!self::$routesLoaded) {
            if (!$this->debug && file_exists(ROOT_DIR . $this->cacheFile)) {
                self::$routes = require ROOT_DIR . $this->cacheFile;
            } else {
                $this->loadRoutes();
                if (!$this->debug) {
                    $this->cacheRoutes();
                }
            }
            self::$routesLoaded = true;
        }
    }

    private function loadRoutes()
    {
        // Clear existing routes
        self::$routes = [];
        
        // Make $router available to routes.php
        $router = $this;
        require_once ROOT_DIR . '/config/routes.php';
        
        if ($this->debug) {
            $this->log("Routes loaded");
        }
    }

    private function cacheRoutes()
    {
        $content = '<?php return ' . var_export(self::$routes, true) . ';';
        file_put_contents(ROOT_DIR . $this->cacheFile, $content);
    }

    /**
     * Add a route to the router
     *
     * @param string $method HTTP method
     * @param string $pattern URL pattern (regex)
     * @param mixed $handler Callback or Controller@method string
     * @return void
     */
    public function add($method, $pattern, $handler)
    {
        // Ensure pattern starts with /
        $pattern = '/' . ltrim($pattern, '/');
        
        self::$routes[$method][] = [
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Register a GET route
     *
     * @param string $pattern URL pattern
     * @param mixed $handler Callback or Controller@method string
     * @return void
     */
    public function get($pattern, $handler)
    {
        $pattern = '/' . ltrim($pattern, '/');
        self::$routes['GET'][$pattern] = [
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Register a POST route
     *
     * @param string $pattern URL pattern
     * @param mixed $handler Callback or Controller@method string
     * @return void
     */
    public function post($pattern, $handler)
    {
        $pattern = '/' . ltrim($pattern, '/');
        self::$routes['POST'][$pattern] = [
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Register a PUT route
     */
    public function put($pattern, $handler)
    {
        $pattern = '/' . ltrim($pattern, '/');
        self::$routes['PUT'][$pattern] = [
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Register a DELETE route
     */
    public function delete($pattern, $handler)
    {
        $pattern = '/' . ltrim($pattern, '/');
        self::$routes['DELETE'][$pattern] = [
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function setDebug($debug = true)
    {
        $this->debug = $debug;
        return $this;
    }

    protected function compilePattern($pattern)
    {
        if (isset($this->routeCache[$pattern])) {
            return $this->routeCache[$pattern];
        }
        
        // If pattern is empty or just '/', return root pattern
        if (empty($pattern) || $pattern === '/') {
            $compiled = '#^/?$#i';  // Allow optional trailing slash
        } else {
            // Remove leading/trailing slashes
            $pattern = trim($pattern, '/');
            
            // Replace simple numeric patterns
            $pattern = str_replace('(\d+)', '([0-9]+)', $pattern);
            
            // Replace named parameters
            $pattern = preg_replace('/\{([a-zA-Z][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $pattern);
            
            // Replace optional segments
            $pattern = preg_replace('/\(([^()]*)\)\?/', '(?:$1)?', $pattern);
            
            // Replace wildcards
            $pattern = str_replace('*', '[^/]+', $pattern);
            
            // Escape slashes and add delimiters
            $compiled = '#^' . str_replace('/', '\\/', $pattern) . '/?$#i';  // Allow optional trailing slash
        }
        
        $this->routeCache[$pattern] = $compiled;
        $this->cache->set('route.patterns', $this->routeCache);
        
        return $compiled;
    }

    /**
     * Compile the routes into a format suitable for dispatching
     *
     * @return array
     */
    public function compile()
    {
        $compiled = [];
        foreach (self::$routes as $method => $routes) {
            foreach ($routes as $route) {
                $compiled[] = [
                    'method' => $method,
                    'pattern' => $this->compilePattern($route['pattern']),
                    'handler' => $route['handler']
                ];
            }
        }
        return $compiled;
    }

    /**
     * Set the compiled routes
     *
     * @param array $routes
     * @return void
     */
    public function setRoutes($routes)
    {
        $this->compiledRoutes = $routes;
    }

    protected function log($message)
    {
        // Only log errors in debug mode
        if ($this->debug && strpos($message, 'Error') !== false) {
            error_log("[Router] " . $message);
        }
    }

    /**
     * Dispatch the request to the appropriate handler
     *
     * @return Response
     * @throws RuntimeException
     */
    public function dispatch()
    {
        $method = $this->request->getMethod();
        $uri = '/' . trim($this->request->getUri(), '/');
        
        // Check if route exists for this method
        if (!isset(self::$routes[$method])) {
            throw new \Exception('Method not allowed', 405);
        }
        
        // Look for exact match first
        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            list($controller, $action) = explode('@', $route['handler']);
            $controller = "App\\Controllers\\{$controller}";
            
            $instance = new $controller();
            return $instance->$action($this->request);
        }
        
        throw new \Exception('Route not found', 404);
    }
} 