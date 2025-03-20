<?php
namespace App\Core;

class Router {

    private array $routes = [];

    /**
     * Register a GET route.
     *
     * @param string $uri The URI to match.
     * @param callable $callback The callback to execute.
     */
    public function get(string $uri, mixed $callback): void
    {
        $this->routes['GET'][$uri] = $callback;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri The URI to match.
     * @param callable $callback The callback to execute.
     */
    public function post(string $uri, mixed $callback): void
    {
        $this->routes['POST'][$uri] = $callback;
    }

    /**
     * Register a PUT route.
     *
     */
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
    
        try {
            if ($uri === '/' && $method === 'GET') {
                echo file_get_contents(__DIR__ . '/../../public/index.html');
                return;
            }
    
            if (isset($this->routes[$method][$uri])) {
                $callback = $this->routes[$method][$uri];
                call_user_func([new $callback[0](), $callback[1]]);
            } else {
                throw new \Exception("Route not found", 404);
            }
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    
}