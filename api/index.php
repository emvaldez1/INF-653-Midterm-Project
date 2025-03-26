<?php
// File: index.php (top-level)
header('Content-Type: application/json');

// Optionally, you might want to list available endpoints or provide a basic message.
echo json_encode([
    'message' => 'Welcome to the Quotes API. Available endpoints: /api/quotes, /api/authors, /api/categories'
]);
?>
