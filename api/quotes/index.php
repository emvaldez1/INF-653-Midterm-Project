<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $quote->id = $_GET['id'] ?? null;
        $quote->author_id = $_GET['author_id'] ?? null;
        $quote->category_id = $_GET['category_id'] ?? null;
        $response = $quote->id ? $quote->readSingle() : $quote->read();
        http_response_code(200);
        echo json_encode($response);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;
            if ($quote->create()) {
                http_response_code(201);
                echo json_encode([
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'author_id' => $quote->author_id,
                    'category_id' => $quote->category_id
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Quote Not Created']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
            $quote->id = $data->id;
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;
            if ($quote->update()) {
                http_response_code(200);
                echo json_encode([
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'author_id' => $quote->author_id,
                    'category_id' => $quote->category_id
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Quote Not Found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $quote->id = $data->id;
            if ($quote->delete()) {
                http_response_code(200);
                echo json_encode(['id' => $quote->id]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Quote Not Found']);
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
