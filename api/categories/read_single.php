<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$category->id = isset($_GET['id']) ? $_GET['id'] : die();

$category->readSingle();

if($category->name != null) {
    $category_arr = array(
        'id' => $category->id,
        'category' => $category->name
    );

    echo json_encode($category_arr);
} else {
    echo json_encode(array('message' => 'Category Not Found'));
}
