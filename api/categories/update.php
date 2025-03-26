<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->category)) {
    $category->id = $data->id;
    $category->category = $data->category;

    if ($category->update()) {
        echo json_encode([
            'id' => $category->id,
            'category' => $category->category
        ]);
    } else {
        echo json_encode(['message' => 'No Categories Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
