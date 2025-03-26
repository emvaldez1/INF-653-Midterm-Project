<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Initialize the database and connect
$database = new Database();
$db = $database->connect();

// Check if the database connection was successful
if (!$db) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

$author = new Author($db);

// Check if ID is provided and is a valid number
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing or invalid ID']);
    exit;
}

$author->id = $_GET['id'];
$author->readSingle();

// Check if an author was found
if ($author->author != null) {
    $author_arr = [
        'id' => $author->id,
        'author' => $author->author
    ];
    http_response_code(200); // OK
    echo json_encode($author_arr);
} else {
    http_response_code(404); // Not Found
    echo json_encode(['message' => 'Author Not Found with ID ' . $author->id]);
}
