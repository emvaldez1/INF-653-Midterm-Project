<?php
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
    $num = $result->rowCount();

    if ($num > 0) {
        $categories_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $category_item = array(
                'id' => $id,
                'category' => $category
            );
            $categories_arr[] = $category_item; // Changed from array_push for performance
        }

        echo json_encode($categories_arr); // Directly returning the array
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Categories Found']);
    }
}
?>
