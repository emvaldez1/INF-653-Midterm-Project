<?php
// Set headers for CORS and content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate DB and connect
$database = new Database();
$db = $database->connect();

// Instantiate quote object
$quote = new Quote($db);

// Check for ID in URL and assign to object
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

// Read quote
$quote->readSingle();

if ($quote->quote != null) {
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_id,
        'category' => $quote->category_id
    );

    echo json_encode($quote_arr);
} else {
    echo json_encode(['message' => 'No Quotes Found']);
}
