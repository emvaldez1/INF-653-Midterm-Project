<?php
// Headers required for CORS and Content-Type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once 'Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate an Author object
$author = new Author($db);

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Fetch a single author by id
            $author->id = $_GET['id'];
            $author->readSingle();
            if ($author->author != null) {
                $author_arr = array(
                    'id' => $author->id,
                    'author' => $author->author
                );
                echo json_encode($author_arr);
            } else {
                echo json_encode(array('message' => 'Author Not Found'));
            }
        } else {
            // Fetch all authors
            $result = $author->read(); 
            $num = $result->rowCount();

            if ($num > 0) {
                $authors_arr = array();
                $authors_arr['data'] = array();

                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $author_item = array(
                        'id' => $id,
                        'author' => $author
                    );
                    array_push($authors_arr['data'], $author_item);
                }

                echo json_encode($authors_arr);
            } else {
                echo json_encode(array('message' => 'No Authors Found'));
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
        echo json_encode(array('message' => 'Method Not Allowed'));
        break;
}
