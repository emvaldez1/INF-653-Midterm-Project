<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize the database and connect
$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

$quote = new Quote($db);

// Get ID from URL
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

$quote->readSingle();

if ($quote->quote != null) {
    // Create array
    $quote_arr = array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_name,
        'category' => $quote->category_name
    );

    // Make JSON
    http_response_code(200); // OK
    echo json_encode($quote_arr);
} else {
    // Not found
    http_response_code(404); // Not Found
    echo json_encode(
        array('message' => 'No Quote Found with ID ' . $quote->id)
    );
}
