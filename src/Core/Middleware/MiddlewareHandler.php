<?php

namespace Blazer\Core\Middleware;

class MiddlewareHandler {
    private $middlewares = [];
    
    public function add($middleware) {
        $this->middlewares[] = $middleware;
    }
    
    public function handle($request, $next) {
        $middleware = array_shift($this->middlewares);
        if ($middleware) {
            return $middleware->handle($request, fn($req) => $this->handle($req, $next));
        }
        return $next($request);
    }
} 