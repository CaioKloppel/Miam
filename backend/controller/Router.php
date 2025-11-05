<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

class Router
{
    private array $routes;
    private string $defaultRoute;
    private string $API_KEY;

    public function __construct(string $defaultRoute){
        $this->defaultRoute = $defaultRoute;
        $this->API_KEY = $_ENV['API_KEY'];
    }

    public function get(string $uri, callable $callback): void{
        $this->routes['GET'][$this->defaultRoute . $uri] = $callback;
    }

    public function post(string $uri, callable $callback): void{
        $this->routes['POST'][$this->defaultRoute . $uri] = $callback;
    }

    public function put(string $uri, callable $callback): void{
        $this->routes['PUT'][$this->defaultRoute . $uri] = $callback;
    }

    public function delete(string $uri, callable $callback): void{
        $this->routes['DELETE'][$this->defaultRoute . $uri] = $callback;
    }

    public function dispatch(string $uri, string $method, string $API_KEY): void{
        if (isset($this->routes[$method][$uri]) and $API_KEY === $this->API_KEY) {
            $callback = $this->routes[$method][$uri];
            $callback();
        } else if ($API_KEY !== $this->API_KEY){
            echo "401 Unauthorized";
        } else {
            echo "404 Not Found";
        } 
    }
}


?>