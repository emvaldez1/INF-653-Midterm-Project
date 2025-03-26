<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $quote->id = $_GET['id'];
            $quote->readSingle();
            if (!empty($quote->quote)) {
                echo json_encode([
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'author' => $quote->author,
                    'category' => $quote->category
                ]);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
            $result = $quote->readByAuthorAndCategory($_GET['author_id'], $_GET['category_id']);
            echo json_encode($result);
        } elseif (isset($_GET['author_id'])) {
            $result = $quote->readByAuthor($_GET['author_id']);
            echo json_encode($result);
        } elseif (isset($_GET['category_id'])) {
            $result = $quote->readByCategory($_GET['category_id']);
            echo json_encode($result);
        } else {
            $result = $quote->read();
            echo json_encode($result);
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
