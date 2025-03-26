<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Initialize the database and connect
$database = new Database();
$db = $database->connect();

// Ensure the connection was successful
if (!$db) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

$quote = new Quote($db);

// Ensure the ID is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing or invalid ID']);
    exit;
}

$quote->id = $_GET['id'];
$quote->readSingle();

if ($quote->quote != null) {
    $quote_arr = [
        'id' => $quote->id,
        'quote' => $quote->quote,
        'author' => $quote->author_name,
        'category' => $quote->category_name
    ];
    http_response_code(200); // OK
    echo json_encode($quote_arr);
} else {
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'No Quote Found with ID ' . $quote->id]);
}
