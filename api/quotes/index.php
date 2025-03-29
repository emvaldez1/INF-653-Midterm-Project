<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

if ($id) {
    if ($quote->read_single($id)) {
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        );
        echo json_encode($quote_arr);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    $result = $quote->read_filtered($author_id, $category_id);
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
}
?>
