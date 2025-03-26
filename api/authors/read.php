<?php
// If an 'id' parameter is present, delegate to read a single author
if (isset($_GET['id'])) {
    // Prepare the author with the given id
    $author->id = intval($_GET['id']);
    if ($author->read_single()) {
        // Author found, output JSON
        echo json_encode([
            'id' => $author->id,
            'author' => $author->author
        ]);
    } else {
        // Author not found
        http_response_code(404);
        echo json_encode(['message' => 'authorId Not Found']);
    }
} else {
    // No ID specified: retrieve all authors
    $result = $author->read();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $authorsArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $authorsArr[] = [
                'id' => $row['id'],
                'author' => $row['author']
            ];
        }
        echo json_encode($authorsArr);
    } else {
        echo json_encode(['message' => 'No Authors Found']);
    }
}
exit();
?>
