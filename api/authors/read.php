<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);

$result = $author->read();
$num = $result->rowCount();

if ($num > 0) {
    $authors_arr = [];
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = [
            'id' => $id,
            'author' => $author
        ];
        array_push($authors_arr, $author_item);
    }
    echo json_encode($authors_arr);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'No Authors Found']);
}
