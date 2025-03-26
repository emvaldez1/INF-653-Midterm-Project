<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $author->id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($author->id) {
            $author->readSingle();
            if ($author->author) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        } else {
            $result = $author->read();
            echo json_encode($result->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->author)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $author->author = $data->author;
            if ($author->create()) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                echo json_encode(['message' => 'Author Not Created']);
            }
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->id) || empty($data->author)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $author->id = $data->id;
            $author->author = $data->author;
            if ($author->update()) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
        } else {
            $author->id = $data->id;
            if ($author->delete()) {
                echo json_encode(['id' => $author->id]);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
