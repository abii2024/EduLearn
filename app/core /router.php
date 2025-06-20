<?php

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, callable $action): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = strtok($uri, '?'); // Remove query string
        if (isset($this->routes[$method][$uri])) {
            $this->routes[$method][$uri]();
        } else {
            http_response_code(404);
            echo "404 - Pagina niet gevonden";
        }
    }
}
