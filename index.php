<?php
// Example structure for `index.php` in `/api`
header('Content-Type: application/json');

$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$apiIndex = array_search('api', $requestUri);

if (isset($requestUri[$apiIndex + 1])) {
    $resource = $requestUri[$apiIndex + 1];
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
            http_response_code(404);
            echo json_encode(['message' => 'Resource not found']);
            break;
    }
} else {
    echo json_encode(['message' => 'No resource specified']);
}
