<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

if (isset($_GET['id'])) {
    $author->id = $_GET['id'];

    if($author->readSingle()) {
        echo json_encode([
            'id' => $author->id,
            'author' => $author->author
        ]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'Author Not Found']);
    }
} else {
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
        http_response_code(404); // Not Found
        echo json_encode(['message' => 'No Authors Found']);
    }
}
exit();
?>
