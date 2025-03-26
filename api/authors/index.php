<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $author->id = $_GET['id'];
            $author->readSingle();
            if ($author->author) {
                http_response_code(200);
                echo json_encode([
                    'id' => $author->id,
                    'author' => $author->author
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No Authors Found']);
            }
        } else {
            $result = $author->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $authors_arr = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $authors_arr[] = [
                        'id' => $row['id'],
                        'author' => $row['author']
                    ];
                }
                http_response_code(200);
                echo json_encode($authors_arr);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No Authors Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->author)) {
            $author->author = $data->author;
            if ($author->create()) {
                http_response_code(201);
                echo json_encode([
                    'id' => $author->id,
                    'author' => $author->author
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Author Not Created']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->author)) {
            $author->id = $data->id;
            $author->author = $data->author;
            if ($author->update()) {
                http_response_code(200);
                echo json_encode([
                    'id' => $author->id,
                    'author' => $author->author
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Author Not Found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $author->id = $data->id;
            if ($author->delete()) {
                http_response_code(200);
                echo json_encode(['id' => $author->id]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Author Not Found']);
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
