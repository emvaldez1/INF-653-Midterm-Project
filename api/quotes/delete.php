<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {
    $quote->id = $data->id;

    if ($quote->delete()) {
        echo json_encode(['id' => $quote->id]);
    } else {
        http_response_code(500); // Server error
        echo json_encode(['message' => 'Quote Not Deleted']);
    }
} else {
    http_response_code(400); // Bad request
    echo json_encode(['message' => 'Missing Required Parameters']);
}
exit();
?>
