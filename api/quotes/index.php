<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();
$quote = new Quote($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Handle query parameters for filtering if necessary
        $quote->id = $_GET['id'] ?? null;
        if (!empty($quote->id)) {
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
            // If no specific id, return all quotes
            $result = $quote->read();
            $num = $result->rowCount();
            if ($num > 0) {
                $quotes_arr = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $quotes_arr[] = [
                        'id' => $row['id'],
                        'quote' => $row['quote'],
                        'author' => $row['author'],
                        'category' => $row['category']
                    ];
                }
                echo json_encode($quotes_arr);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
            }
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
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
        } else {
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
                echo json_encode([
                    'id' => $quote->id,
                    'quote' => $quote->quote,
                    'author_id' => $quote->author_id,
                    'category_id' => $quote->category_id
                ]);
            } else {
                echo json_encode(['message' => 'Quote Not Updated']);
            }
        } else {
            echo json_encode(['message' => 'Missing Required Parameters']);
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->id)) {
            $quote->id = $data->id;
            if ($quote->delete()) {
                echo json_encode(['id' => $quote->id]);
            } else {
                echo json_encode(['message' => 'No Quotes Found']);
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
?>
