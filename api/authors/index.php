<?php
// File: api/authors/index.php
// Allow any origin and JSON content type
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Handle CORS preflight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit(0); // Stop further execution for OPTIONS
}

// Include database and model classes
require_once '../../config/Database.php';
require_once '../../models/Author.php';
require_once '../../models/Category.php';   // Might be used in cross-checks for quotes
require_once '../../models/Quote.php';      // Might be used in cross-checks

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate models
$author = new Author($db);
$category = new Category($db);
$quote = new Quote($db);

// Route to appropriate method script
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        include 'read.php';    break;
    case 'POST':
        include 'create.php';  break;
    case 'PUT':
        include 'update.php';  break;
    case 'DELETE':
        include 'delete.php';  break;
    default:
        // If an unsupported method is called, return 405 Method Not Allowed
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
}
?>
