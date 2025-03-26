<?php
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a category object
$category = new Category($db);

// Check if ID is provided for single category, else get all
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $category->id = $id;
    if ($category->read_single()) {
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category
        );
        echo json_encode($category_arr);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Category Not Found']);
    }
} else {
    $result = $category->read();
    $categories_arr = array();
    $categories_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $category_item = array(
            'id' => $id,
            'category' => $category
        );
        array_push($categories_arr['data'], $category_item);
    }

    echo json_encode($categories_arr);
}
?>
