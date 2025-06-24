<?php
// core/router.php

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(string $method, string $uri, callable $action): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = strtok($uri, '?'); // verwijder querystring

        // First try exact match (handles static routes like /classes/create)
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];

            // Als het een callable is (function)
            if (is_callable($action)) {
                call_user_func($action);
                return;
            }
        }

        // Try dynamic routes with parameters (like /classes/{id})
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            // Skip if this is the exact route we already tried
            if ($route === $uri) {
                continue;
            }
            
            if ($this->matchRoute($route, $uri)) {
                $params = $this->extractParams($route, $uri);
                
                if (is_callable($action)) {
                    // Pass parameters to the action
                    if (!empty($params)) {
                        call_user_func_array($action, $params);
                    } else {
                        call_user_func($action);
                    }
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 - Pagina '$uri' niet gevonden.";
    }
    
    private function matchRoute(string $route, string $uri): bool
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';
        
        return preg_match($pattern, $uri);
    }
    
    private function extractParams(string $route, string $uri): array
    {
        // Extract parameter names from route
        preg_match_all('/\{([^}]+)\}/', $route, $paramNames);
        
        // Extract parameter values from URI
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';
        
        preg_match($pattern, $uri, $paramValues);
        
        $params = [];
        if (isset($paramNames[1]) && count($paramNames[1]) > 0) {
            // Remove the full match from paramValues
            array_shift($paramValues);
            
            // Return just the parameter values in order
            return $paramValues;
        }
        
        return $params;
    }
}
