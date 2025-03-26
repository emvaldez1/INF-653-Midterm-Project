<?php
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    $quote->id = intval($data->id);
    if (!$quote->read_single()) {
        http_response_code(404);
        echo json_encode(['message' => 'No Quotes Found']);
    } else {
        if ($quote->delete()) {
            echo json_encode(['id' => $quote->id]);
        } else {
            echo json_encode(['message' => 'Quote Not Deleted']);
            http_response_code(500);
        }
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
    http_response_code(400);
}
exit();
?>
