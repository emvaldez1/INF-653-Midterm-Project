<?php
// Headers required for CORS and Content-Type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a Quote object
$quote = new Quote($db);

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            include 'read_single.php';
        } elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
            $quote->author_id = $_GET['author_id'];
            $quote->category_id = $_GET['category_id'];
            $result = $quote->readByAuthorAndCategory();
        } elseif (isset($_GET['author_id'])) {
            $quote->author_id = $_GET['author_id'];
            $result = $quote->readByAuthor();
        } elseif (isset($_GET['category_id'])) {
            $quote->category_id = $_GET['category_id'];
            $result = $quote->readByCategory();
        } else {
            include 'read.php';
            break;
        }

        $num = $result->rowCount();
        if ($num > 0) {
            $quotes_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $quote_item = array(
                    'id' => $id,
                    'quote' => $quote,
                    'author' => $author,
                    'category' => $category
                );
                array_push($quotes_arr, $quote_item);
            }
            echo json_encode($quotes_arr);
        } else {
            echo json_encode(array('message' => 'No Quotes Found'));
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
