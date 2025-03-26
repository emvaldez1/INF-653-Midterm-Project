<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Category.php';

$database = new Database();
$db = the database->connect();

$category = new Category($db);

$category->id = isset($_GET['id']) ? $_GET['id'] : die();

$category->readSingle();

if($category->category != null) {
    http_response_code(200);
    $category_arr = array(
        'id' => $category->id,
        'category' => $category->category
    );
    echo json_encode($category_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Category Not Found'));
}
