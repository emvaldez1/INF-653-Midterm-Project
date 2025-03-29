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

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate author object
$author = new Author($db);

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $author->id = $id;
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
    $num = $result->rowCount();

    if ($num > 0) {
        $authors_arr = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $author_item = array(
                'id' => $id,
                'author' => $author
            );
            $authors_arr[] = $author_item; // Changed from array_push for performance
        }

        echo json_encode($authors_arr); // Directly returning the array
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No Authors Found']);
    }
}
?>
