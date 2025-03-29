<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    if ($author->read_single()) {
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author
        );
        echo json_encode($author_arr);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Author Not Found']);
    }
} else {
    $result = $author->read();
    $authors_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array(
            'id' => $id,
            'author' => $author
        );
        array_push($authors_arr, $author_item);
    }
    echo json_encode($authors_arr);
}
?>
