<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

if (!$db) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => 'Database connection failed']);
    exit;
}

$author = new Author($db);
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->author)) {
    $author->author = $data->author;
    if ($author->create()) {
        http_response_code(201);
        echo json_encode([
            'id' => $db->lastInsertId(),
            'author' => $author->author
        ]);
    } else {
        http_response_code(503); // Service Unavailable
        echo json_encode(['message' => 'Author Not Created']);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
