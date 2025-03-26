<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $category->id = isset($_GET['id']) ? $_GET['id'] : null;
        $response = $category->id ? $category->readSingle() : $category->read();
        http_response_code(200);
        echo json_encode($response);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->category)) {
            $category->category = $data->category;
            if ($category->create()) {
                http_response_code(201);
                echo json_encode(['id' => $category->id, 'category' => $category->category]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Category Not Created']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->category)) {
            $category->id = $data->id;
            $category->category = $data->category;
            if ($category->update()) {
                http_response_code(200);
                echo json_encode(['id' => $category->id, 'category' => $category->category]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Category Not Found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $category->id = $data->id;
            if ($category->delete()) {
                http_response_code(200);
                echo json_encode(['id' => $category->id]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Category Not Found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
