<?php
if (isset($_GET['id'])) {
    $category->id = intval($_GET['id']);
    if ($category->read_single()) {
        echo json_encode([
            'id' => $category->id,
            'category' => $category->category
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'categoryId Not Found']);
    }
} else {
    $result = $category->read();
    $rowCount = $result->rowCount();
    if ($rowCount > 0) {
        $categoriesArr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categoriesArr[] = [
                'id' => $row['id'],
                'category' => $row['category']
            ];
        }
        echo json_encode($categoriesArr);
    } else {
        echo json_encode(['message' => 'No Categories Found']);
    }
}
exit();
?>
