<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $author->id = $data->id;

    if ($author->delete()) {
        http_response_code(200); // HTTP 200 OK
        echo json_encode(['id' => $author->id]);
    } else {
        http_response_code(404); // HTTP 404 Not Found
        echo json_encode(['message' => 'Author Not Found']);
    }
} else {
    http_response_code(400); // HTTP 400 Bad Request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
