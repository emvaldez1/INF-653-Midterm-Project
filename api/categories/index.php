<?php
// CORS and content-type headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate category object
$category = new Category($db);

// Check if ID is provided for single category
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
        echo json_encode(['message' => 'category_id Not Found']);
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
