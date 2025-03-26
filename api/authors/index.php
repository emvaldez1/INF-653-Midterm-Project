<?php

ini_set('display_errors', 0); // Turn off displaying errors
error_reporting(E_ALL); // Report all errors internally


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
        $author->id = isset($_GET['id']) ? $_GET['id'] : null;

        if ($author->id) {
            $author->readSingle();
            if ($author->author) {
                echo json_encode([
                    'id' => $author->id,
                    'author' => $author->author
                ]);
            } else {
                echo json_encode(['message' => 'author_id Not Found']);
            }
        } else {
            $result = $author->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $authors_arr = [];

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $authors_arr[] = [
                        'id' => $id,
                        'author' => $author
                    ];
                }
                echo json_encode($authors_arr);
            } else {
                echo json_encode(['message' => 'No Authors Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->author)) {
            $author->author = $data->author;

            if ($author->create()) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                echo json_encode(['message' => 'Author Not Created']);
            }
        } else {
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id) && !empty($data->author)) {
            $author->id = $data->id;
            $author->author = $data->author;

            if ($author->update()) {
                echo json_encode(['id' => $author->id, 'author' => $author->author]);
            } else {
                echo json_encode(['message' => 'Author Not Updated']);
            }
        } else {
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id)) {
            $author->id = $data->id;

            if ($author->delete()) {
                echo json_encode(['id' => $author->id]);
            } else {
                echo json_encode(['message' => 'No Authors Found']);
            }
        } else {
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
