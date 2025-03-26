<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    $author->id = intval($data->id);
    // Verify the author exists
    if (!$author->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'authorId Not Found']);
    } else {
        // Try to delete
        if ($author->delete()) {
            echo json_encode(['id' => $author->id]);
        } else {
            echo json_encode(['message' => 'Author Not Deleted']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
