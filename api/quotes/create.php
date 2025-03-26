<?php
$data = json_decode(file_get_contents("php://input"));
// Validate all required fields: 'quote', 'authorId', 'categoryId'
if (!empty($data->quote) && !empty($data->authorId) && !empty($data->categoryId)) {
    // Assign data
    $quote->quote = $data->quote;
    $quote->authorId = intval($data->authorId);
    $quote->categoryId = intval($data->categoryId);

    // Verify that provided authorId exists
    $author->id = $quote->authorId;
    $authorExists = $author->read_single();

    // Verify that provided categoryId exists
    $category->id = $quote->categoryId;
    $categoryExists = $category->read_single();

    if (!$authorExists || !$categoryExists) {
        // If either foreign key doesn't exist, do not create quote
        http_response_code(404);
        if (!$authorExists && !$categoryExists) {
            echo json_encode(['message' => 'authorId and categoryId Not Found']);
        } else if (!$authorExists) {
            echo json_encode(['message' => 'authorId Not Found']);
        } else {
            echo json_encode(['message' => 'categoryId Not Found']);
        }
    } else {
        // Both foreign keys are valid, attempt to create quote
        if ($quote->create()) {
            $newId = $db->lastInsertId();
            // Fetch the newly created quote to include author/category names in response
            $quote->id = $newId;
            $quote->read_single();
            echo json_encode([
                'id' => $newId,
                'quote' => $quote->quote,
                'author' => $quote->author,
                'category' => $quote->category
            ]);
            http_response_code(201);
        } else {
            echo json_encode(['message' => 'Quote Not Created']);
            http_response_code(500);
        }
    }
} else {
    // If any required field is missing
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
