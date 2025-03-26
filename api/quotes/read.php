<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];

    if($quote->readSingle()) {
        echo json_encode([
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author_id' => $quote->author_id,
            'category_id' => $quote->category_id
        ]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'No Quote Found']);
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
                'author_id' => $author_id,
                'category_id' => $category_id
            ];

            array_push($quotes_arr, $quote_item);
        }

        echo json_encode($quotes_arr);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'No Quotes Found']);
    }
}
exit();
?>
