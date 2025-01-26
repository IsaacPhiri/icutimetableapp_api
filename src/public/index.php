<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $router = new \Core\Router();
    
    // Apply middleware
    (new \Middleware\AuthMiddleware())->handle();
    
    // Define routes
    $router->addRoute('GET', '/users', function() {
        $controller = new \Controllers\UserController();
        return $controller->index();
    });
    
    // Add more routes here...
    
    // Route the request
    echo $router->dispatch();
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'message' => 'Internal Server Error',
        'error' => $e->getMessage()
    ]);
} 