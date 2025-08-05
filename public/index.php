<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load routes
$router = require_once __DIR__ . '/../app/routes.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Handle PUT and DELETE requests (for forms that don't support them natively)
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Dispatch the request
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    // Log the error
    error_log('Application error: ' . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    echo 'An error occurred. Please try again later.';
} 