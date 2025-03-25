<?php
header('Content-Type: application/json');

// Parse the request URI to determine the endpoint
$request = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $request);
$base = array_shift($parts); // This would be 'api' if the URI is like 'api/authors'

// Route the request to the appropriate handler based on the first segment after 'api'
if ($base === 'api') {
    $resource = array_shift($parts);

    switch ($resource) {
        case 'authors':
            require 'authors/index.php'; // Ensure this script handles the rest of the URI.
            break;
        case 'categories':
            require 'categories/index.php'; // Ensure this script handles the rest of the URI.
            break;
        case 'quotes':
            require 'quotes/index.php'; // Ensure this script handles the rest of the URI.
            break;
        default:
            echo json_encode([
                'message' => 'Welcome to INF653 Midterm Project API!',
                'endpoints' => [
                    '/api/authors/',
                    '/api/categories/',
                    '/api/quotes/'
                ]
            ]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Resource not found']);
}
?>
