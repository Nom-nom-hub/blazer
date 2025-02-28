<?php

namespace Blazer\Core;

class Request
{
    /**
     * URI path
     *
     * @var string
     */
    protected $uri;
    
    /**
     * HTTP method
     *
     * @var string
     */
    protected $method;
    
    /**
     * Query parameters
     *
     * @var array
     */
    protected $query = [];
    
    /**
     * Request body parameters
     *
     * @var array
     */
    protected $body = [];
    
    /**
     * Request headers
     *
     * @var array
     */
    protected $headers = [];
    
    /**
     * Cookies
     *
     * @var array
     */
    protected $cookies = [];

    /**
     * Create a new Request instance from globals
     */
    public function __construct()
    {
        $this->uri = $this->parseUri();
        $this->method = $this->parseMethod();
        $this->query = $_GET;
        $this->body = $this->parseBody();
        $this->headers = $this->parseHeaders();
        $this->cookies = $_COOKIE;
    }

    /**
     * Parse URI from server variables
     *
     * @return string
     */
    protected function parseUri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove trailing slash
        $uri = rtrim($uri, '/');
        
        // If empty, use root
        return $uri ?: '/';
    }

    /**
     * Parse HTTP method from server variables
     *
     * @return string
     */
    protected function parseMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Check for method override via _method form field or header
        if ($method === 'POST') {
            if (isset($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $method = strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            }
        }
        
        return $method;
    }

    /**
     * Parse request body based on content type
     *
     * @return array
     */
    protected function parseBody()
    {
        $body = [];
        
        // Handle different content types
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (strpos($contentType, 'application/json') !== false) {
                $input = file_get_contents('php://input');
                $body = json_decode($input, true) ?? [];
            } else {
                $body = $_POST;
            }
        } elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            $input = file_get_contents('php://input');
            
            // Try to parse JSON
            if (strpos($contentType, 'application/json') !== false) {
                $body = json_decode($input, true) ?? [];
            } else {
                // Parse form-urlencoded data
                parse_str($input, $body);
            }
        }
        
        return $body;
    }

    /**
     * Parse HTTP headers from server variables
     *
     * @return array
     */
    protected function parseHeaders()
    {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$name] = $value;
            }
        }
        
        return $headers;
    }

    /**
     * Get URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get a query parameter
     *
     * @param string|null $key Parameter name or null for all
     * @param mixed $default Default value if parameter not found
     * @return mixed
     */
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        
        return $this->query[$key] ?? $default;
    }

    /**
     * Get a body parameter
     *
     * @param string|null $key Parameter name or null for all
     * @param mixed $default Default value if parameter not found
     * @return mixed
     */
    public function body($key = null, $default = null)
    {
        if ($key === null) {
            return $this->body;
        }
        
        return $this->body[$key] ?? $default;
    }

    /**
     * Get an input parameter (from query or body)
     *
     * @param string|null $key Parameter name or null for all
     * @param mixed $default Default value if parameter not found
     * @return mixed
     */
    public function input($key = null, $default = null)
    {
        if ($key === null) {
            return array_merge($this->query, $this->body);
        }
        
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Get a header
     *
     * @param string|null $key Header name or null for all
     * @param mixed $default Default value if header not found
     * @return mixed
     */
    public function header($key = null, $default = null)
    {
        if ($key === null) {
            return $this->headers;
        }
        
        // Normalize header name
        $key = str_replace(' ', '-', ucwords(strtolower(str_replace(['-', '_'], ' ', $key))));
        
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get a cookie
     *
     * @param string|null $key Cookie name or null for all
     * @param mixed $default Default value if cookie not found
     * @return mixed
     */
    public function cookie($key = null, $default = null)
    {
        if ($key === null) {
            return $this->cookies;
        }
        
        return $this->cookies[$key] ?? $default;
    }
} 