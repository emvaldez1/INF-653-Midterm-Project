<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->category)) {
    $category->category = $data->category;

    if ($category->create()) {
        echo json_encode([
            'id' => $category->id,
            'category' => $category->category
        ]);
        http_response_code(201); // Created
    } else {
        http_response_code(500); // Server error
        echo json_encode(['message' => 'Category Not Created']);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
exit();
?>
