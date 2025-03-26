<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Author.php';

$database = new Database();
$db = the database->connect();

$author = new Author($db);

$author->id = isset($_GET['id']) ? $_GET['id'] : die();

$author->readSingle();

if($author->author != null) {
    http_response_code(200);
    $author_arr = array(
        'id' => $author->id,
        'author' => $author->author
    );
    echo json_encode($author_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'Author Not Found'));
}
