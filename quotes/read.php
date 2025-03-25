<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../config/Database.php';
include_once 'Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$result = $quote->read();
$num = $result->rowCount();

if ($num > 0) {
    $quotes_arr = array();
    $quotes_arr['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      
        $quote_item = array(
            'id' => $row['id'],
            'quote' => $row['quote'],
            'author' => $row['author'], 
            'category' => $row['category'] 
        );
        array_push($quotes_arr['data'], $quote_item);
    }

    echo json_encode($quotes_arr);
} else {
    echo json_encode(array('message' => 'No Quotes Found'));
}
