<?php
namespace Middleware;

class AuthMiddleware {
    public function handle() {
        // Add authentication logic here
        // For now, just pass through
        return true;
    }
} 