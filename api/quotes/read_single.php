<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = the database->connect();

$quote = new Quote($db);

$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

$quote->readSingle();

if ($quote->quote != null) {
    http_response_code(200);
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_id,
        'category' => $quote->category_id
    );
    echo json_encode($quote_arr);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No Quotes Found']);
}
