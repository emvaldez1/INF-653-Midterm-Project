<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->author)) {
    $author->author = $data->author;
    if ($author->create()) {
        // Creation successful, return the new record with its ID
        $newId = $db->lastInsertId();  // works for PostgreSQL if sequence is set
        echo json_encode([
            'id' => $newId,
            'author' => $author->author
        ]);
        http_response_code(201);  // Created
    } else {
        // In case insertion failed (unlikely here), return error
        echo json_encode(['message' => 'Author Not Created']);
        http_response_code(500);
    }
} else {
    // Missing 'author' field in input
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
