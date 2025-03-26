<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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
        $quote->id = isset($_GET['id']) ? $_GET['id'] : null;
        $quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
        $quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

        if ($quote->id) {
            $quote->readSingle();
            if ($quote->quote) {
                echo json_encode([
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'author' => $quote->author_name,
                    'category' => $quote->category_name
                ]);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        } else {
            $result = $quote->read();
            $num = $result->rowCount();

            if ($num > 0) {
                $quotes_arr = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $quote_item = [
                        'id' => $id,
                        'quote' => $quote,
                        'author' => $author,
                        'category' => $category
                    ];
                    array_push($quotes_arr, $quote_item);
                }
                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if ($quote->create()) {
            echo json_encode([
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author_id' => $quote->author_id,
                'category_id' => $quote->category_id
            ]);
        } else {
            echo json_encode(['message' => 'Quote Not Created']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->id) || empty($data->quote) || empty($data->author_id) || empty($data->category_id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        $quote->id = $data->id;
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;

        if ($quote->update()) {
            echo json_encode([
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author_id' => $quote->author_id,
                'category_id' => $quote->category_id
            ]);
        } else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->id)) {
            echo json_encode(['message' => 'Missing Required Parameters']);
            exit;
        }

        $quote->id = $data->id;

        if ($quote->delete()) {
            echo json_encode(['id' => $quote->id]);
        } else {
            echo json_encode(['message' => 'No Quotes Found']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
