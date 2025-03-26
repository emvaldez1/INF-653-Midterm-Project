<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    $category->id = intval($data->id);
    if (!$category->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'categoryId Not Found']);
    } else {
        if ($category->delete()) {
            echo json_encode(['id' => $category->id]);
        } else {
            echo json_encode(['message' => 'Category Not Deleted']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
