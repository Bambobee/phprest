<?php
// // Allow requests from your React frontend
// header("Access-Control-Allow-Origin: http://localhost:3000");

// // Allow credentials (if needed)
// header("Access-Control-Allow-Credentials: true");

// // Allow specific HTTP methods
// header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// // Allow specific headers
// header("Access-Control-Allow-Headers: Content-Type, Authorization");

// // Handle preflight requests
// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//     http_response_code(204);
//     exit;
// }

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content response
    exit();
}

// Include API logic
require_once( '../app/helpers/functions.php');
$api = new Api;
$api->processApi();

?>
