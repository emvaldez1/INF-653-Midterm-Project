<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->category)) {
    $category->category = $data->category;
    if ($category->create()) {
        http_response_code(201);
        echo json_encode([
            'id' => $db->lastInsertId(),
            'category' => $category->category
        ]);
    } else {
        http_response_code(503);
        echo json_encode(['message' => 'Category Not Created']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
