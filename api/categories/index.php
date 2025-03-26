<?php
// Headers required for CORS and Content-Type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../models/Category.php';


// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate a Category object
$category = new Category($db);

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Fetch a single category by id
            include 'read_single.php';
        } else {
            // Fetch all categories
            include 'read.php';
        }
        break;
    case 'POST':
        // Create a new category
        include 'create.php';
        break;
    case 'PUT':
        // Update an existing category
        include 'update.php';
        break;
    case 'DELETE':
        // Delete a category
        include 'delete.php';
        break;
    default:
        // Method Not Allowed
        http_response_code(405);
        echo json_encode(array('message' => 'Method Not Allowed'));
        break;
}
