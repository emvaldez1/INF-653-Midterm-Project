<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id) && !empty($data->category)) {
    $category->id = intval($data->id);
    $category->category = $data->category;
    if (!$category->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'categoryId Not Found']);
    } else {
        if ($category->update()) {
            echo json_encode([
                'id' => $category->id,
                'category' => $category->category
            ]);
        } else {
            echo json_encode(['message' => 'Category Not Updated']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
