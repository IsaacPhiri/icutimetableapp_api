<?php

namespace Core;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                return call_user_func($route['handler']);
            }
        }
        
        http_response_code(404);
        return json_encode(['error' => 'Route not found']);
    }

    private function matchPath($routePath, $requestPath) {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
        
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }
        
        $params = [];
        for ($i = 0; $i < count($routeParts); $i++) {
            if (strpos($routeParts[$i], '{') === 0) {
                $params[trim($routeParts[$i], '{}')] = $requestParts[$i];
                continue;
            }
            if ($routeParts[$i] !== $requestParts[$i]) {
                return false;
            }
        }
        
        $_GET = array_merge($_GET, $params);
        return true;
    }
} 