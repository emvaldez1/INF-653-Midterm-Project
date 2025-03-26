<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $category->id = $_GET['id'];
            $category->readSingle();
            if (!empty($category->category)) {
                echo json_encode([
                    'id' => $category->id,
                    'category' => $category->category
                ]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        } else {
            $result = $category->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $categories_arr = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $categories_arr[] = [
                        'id' => $id,
                        'category' => $category
                    ];
                }
                echo json_encode($categories_arr);
            } else {
                echo json_encode(['message' => 'No Categories Found']);
            }
        }
        break;

    case 'POST':
        include 'create.php';
        break;

    case 'PUT':
        include 'update.php';
        break;

    case 'DELETE':
        include 'delete.php';
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
