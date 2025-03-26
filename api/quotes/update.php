<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id) && !empty($data->quote) && !empty($data->authorId) && !empty($data->categoryId)) {
    $quote->id = intval($data->id);
    $quote->quote = $data->quote;
    $quote->authorId = intval($data->authorId);
    $quote->categoryId = intval($data->categoryId);

    // Check if quote exists
    if (!$quote->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);  // Quote to update not found
    } 
    // Check if referenced author and category exist
    else if (!$author->read_single() || !$category->read_single()) {
        // We need to check each foreign key individually:
        $author->id = $quote->authorId;
        $category->id = $quote->categoryId;
        $authorExists = $author->read_single();
        $categoryExists = $category->read_single();
        http_response_code(404);
        if (!$authorExists && !$categoryExists) {
            echo json_encode(['message' => 'authorId and categoryId Not Found']);
        } else if (!$authorExists) {
            echo json_encode(['message' => 'authorId Not Found']);
        } else {
            echo json_encode(['message' => 'categoryId Not Found']);
        }
    } 
    else {
        // All good, perform update
        if ($quote->update()) {
            // Fetch updated quote to include names
            $quote->read_single();
            echo json_encode([
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author' => $quote->author,
                'category' => $quote->category
            ]);
        } else {
            echo json_encode(['message' => 'Quote Not Updated']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
