<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json');

$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$api_index = array_search('api', $request);

if ($api_index === false || !isset($request[$api_index + 1])) {
    echo json_encode(['message' => 'API endpoint not found']);
    exit;
}

switch ($request[$api_index + 1]) {
    case 'quotes':
        require 'api/quotes/index.php';
        break;
    case 'authors':
        require 'api/authors/index.php';
        break;
    case 'categories':
        require 'api/categories/index.php';
        break;
    default:
        echo json_encode(['message' => 'API endpoint not found']);
        break;
}
