<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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
            if (!empty($author->author)) {
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
