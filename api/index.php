<?php
header('Content-Type: application/json');

echo json_encode([
    'message' => 'Quotes API',
    'endpoints' => [
        '/api/quotes/',
        '/api/authors/',
        '/api/categories/'
    ]
]);
