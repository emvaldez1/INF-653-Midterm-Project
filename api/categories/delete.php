<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = the database->connect();
$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $category->id = $data->id;

    if ($category->delete()) {
        echo json_encode(['id' => $category->id]);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Category Not Found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
