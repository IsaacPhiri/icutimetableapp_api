<?php

require_once '../db.php';

// Get user_id from the query string
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$user_id) {
    echo json_encode(["error" => "No user ID provided."]);
    exit;
}

// Handle the API request
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        try {
            // Prepare the SQL statement to fetch data for the user
            $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
            $stmt->bind_param("s", $user_id); // Bind the user ID securely
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                echo json_encode($user);
            } else {
                echo json_encode([]);
            }

            $stmt->close();
        } catch (Exception $e) {
            // Handle any unexpected errors
            echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
        }
        break;

    default:
        // Handle unsupported request methods
        echo json_encode(["error" => "Invalid request method."]);
        break;
}

// Close the database connection
$conn->close();
?>
