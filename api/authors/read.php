<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Author.php';

$database = new Database();
$db = $database->connect();
$author = new Author($db);
$result = $author->read();
$num = $result->rowCount();

if ($num > 0) {
    $authors_arr = array();
    $authors_arr['data'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array('id' => $id, 'author' => $author);
        array_push($authors_arr['data'], $author_item);
    }
    echo json_encode($authors_arr);
} else {
    echo json_encode(array('message' => 'No Authors Found'));
}
