<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once 'Quote.php';

$database = new Database();
$db = the database->connect();

$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->id = $data->id;
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if($quote->update()) {
        echo json_encode(array('message' => 'Quote Updated'));
    } else {
        echo json_encode(array('message' => 'Quote Not Updated'));
    }
} else {
    echo json_encode(array('message' => 'Missing required data'));
}
