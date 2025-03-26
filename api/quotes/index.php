<?php
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a quote object
$quote = new Quote($db);

// Check if ID is provided for a single quote, else get all
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $quote->id = $id;
    if ($quote->read_single()) {
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        );
        echo json_encode($quote_arr);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Quote Not Found']);
    }
} else {
    $result = $quote->read();
    $quotes_arr = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $quotes_arr[] = [
            'id' => $row['id'],
            'quote' => $row['quote'],
            'author' => $row['author'],
            'category' => $row['category']
        ];
    }
    echo json_encode($quotes_arr);
}
?>
