<?php

class Router
{
    private array $routes;
    private string $defaultRoute;

    public function __construct(string $defaultRoute)
    {
        $this->defaultRoute = $defaultRoute;
    }

    public function get(string $uri, callable $callback): void
    {
        $this->routes['GET'][$this->defaultRoute . $uri] = $callback;
    }

    public function post(string $uri, callable $callback): void
    {
        $this->routes['POST'][$this->defaultRoute . $uri] = $callback;
    }

    public function dispatch(string $uri, string $method): void
    {
        if (isset($this->routes[$method][$uri])) {
            $callback = $this->routes[$method][$uri];
            $callback();
        } else {
            echo "404 Not Found";
        }
    }
}


?>