<?php
// Allow all origins (safe for this assignment)
header("Access-Control-Allow-Origin: *");

// Allow common headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Allow common methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
