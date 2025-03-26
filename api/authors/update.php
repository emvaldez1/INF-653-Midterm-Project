<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id) && !empty($data->author)) {
    $author->id = intval($data->id);
    $author->author = $data->author;
    // Check if author exists before updating
    if (!$author->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'authorId Not Found']);
    } else {
        if ($author->update()) {
            echo json_encode([
                'id' => $author->id,
                'author' => $author->author
            ]);
        } else {
            echo json_encode(['message' => 'Author Not Updated']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
