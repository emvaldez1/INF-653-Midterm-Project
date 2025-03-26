<?php
// Set the header to ensure we always return JSON
header('Content-Type: application/json');

// Parse the request URI to determine the endpoint
$request = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $request);

// Find the position of 'api' in the URL to correctly determine the endpoint following it
$apiPosition = array_search('api', $parts);

// If 'api' is not found or there's nothing after 'api', return a 404 error
if ($apiPosition === false || !isset($parts[$apiPosition + 1])) {
    http_response_code(404);
    echo json_encode(['message' => 'API endpoint not found']);
    exit;
}

// Get the resource name directly after 'api'
$resource = $parts[$apiPosition + 1] ?? '';

// Route the request to the appropriate file based on the resource
switch ($resource) {
    case 'authors':
        require __DIR__ . '/api/authors/index.php';
        break;
    case 'categories':
        require __DIR__ . '/api/categories/index.php';
        break;
    case 'quotes':
        require __DIR__ . '/api/quotes/index.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'API endpoint not found']);
        break;
}
