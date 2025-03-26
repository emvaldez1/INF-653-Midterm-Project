<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

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
        echo json_encode(['message' => 'No Quotes Found']);
    }
} else {
    echo json_encode(['message' => 'Missing Required Parameters']);
}
