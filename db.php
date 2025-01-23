<?php

header("Content-Type: application/json");

// Define the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Database connection
$conn = new mysqli("localhost", "root", "", "icutimetableapp_db");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Get the URI
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$resource = isset($uri[1]) ? $uri[1] : null;
// $id = isset($uri[2]) && is_numeric($uri[2]) ? intval($uri[2]) : null;
$id = isset($_GET['id']) ? $id = $_GET['id'] : $id = null;

?>
