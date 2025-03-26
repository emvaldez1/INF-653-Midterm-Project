<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate an author object
$author = new Author($db);

// Get ID from URL
$author->id = isset($_GET['id']) ? $_GET['id'] : null;

// Get author
$author->readSingle();

if($author->author != null) {
    // Create array
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );

    // Make JSON
    print_r(json_encode($author_arr));
} else {
    // Set response code - 404 Not found
    http_response_code(404);

    // Author not found
    echo json_encode(array('message' => 'Author Not Found'));
}
