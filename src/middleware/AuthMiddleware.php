<?php
namespace Middleware;

class AuthMiddleware {
    public function handle() {
        $headers = getallheaders();
        
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No authorization token provided']);
            exit;
        }
        
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        
        try {
            // Validate JWT token here
            if (!$this->validateToken($token)) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid token']);
                exit;
            }
            
            return true;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication failed']);
            exit;
        }
    }
    
    private function validateToken($token) {
        // Implement JWT validation logic here
        // For now, return true for testing
        return true;
    }
} 