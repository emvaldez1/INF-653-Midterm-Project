<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->author)) {
    $author->id = $data->id;
    $author->author = $data->author;

    if ($author->update()) {
        echo json_encode([
            'id' => $author->id,
            'author' => $author->author
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Author Not Found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Missing Required Parameters']);
}
