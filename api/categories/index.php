<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();
$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $category->id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($category->id) {
            $category->readSingle();
            if ($category->category) {
                echo json_encode(['id' => $category->id, 'category' => $category->category]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        } else {
            $result = $category->read();
            echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $category->category = $data->category;
            if ($category->create()) {
                echo json_encode(['id' => $category->id, 'category' => $category->category]);
            } else {
                echo json_encode(['message' => 'Category Not Created']);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->id) || empty($data->category)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $category->id = $data->id;
            $category->category = $data->category;
            if ($category->update()) {
                echo json_encode(['id' => $category->id, 'category' => $category->category]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $category->id = $data->id;
            if ($category->delete()) {
                echo json_encode(['id' => $category->id]);
            } else {
                echo json_encode(['message' => 'category_id Not Found']);
            }
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
