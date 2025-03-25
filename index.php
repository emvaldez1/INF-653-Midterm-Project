<?php
header('Content-Type: application/json');
echo json_encode([
    'message' => 'Welcome to INF653 Midterm Project API!',
    'endpoints' => [
        '/api/authors/',
        '/api/categories/',
        '/api/quotes/'
    ]
]);
?>
