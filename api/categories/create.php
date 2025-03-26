<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->category)) {
    $category->category = $data->category;
    if ($category->create()) {
        $newId = $db->lastInsertId();
        echo json_encode([
            'id' => $newId,
            'category' => $category->category
        ]);
        http_response_code(201);
    } else {
        echo json_encode(['message' => 'Category Not Created']);
        http_response_code(500);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
