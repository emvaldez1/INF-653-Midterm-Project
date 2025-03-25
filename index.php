<?php
// Parse the request URI to determine the endpoint
$request = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $request);
$resource = $parts[1] ?? ''; // Assuming the URL structure is something like /api/resource

switch ($resource) {
    case 'authors':
        require 'authors/index.php';
        break;
    case 'categories':
        require 'categories/index.php';
        break;
    case 'quotes':
        require 'quotes/index.php';
        break;
    default:
        echo json_encode(['message' => 'API endpoint not found']);
        http_response_code(404);
        break;
}
