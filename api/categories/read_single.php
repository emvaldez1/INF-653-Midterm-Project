<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

$category = new Category($db);

// Check if ID is provided and is a valid number
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing or invalid ID']);
    exit;
}

$category->id = $_GET['id'];
$category->readSingle();

if ($category->category != null) {
    $category_arr = [
        'id' => $category->id,
        'category' => $category->category
    ];
    http_response_code(200);
    echo json_encode($category_arr);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Category Not Found with ID ' . $category->id]);
}
