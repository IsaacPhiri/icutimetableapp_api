<?php

require_once '../db.php';

// Ensure database connection is established
if (!$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Get user_id from the query string
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$user_id) {
    echo json_encode(["error" => "No user ID provided."]);
    exit;
}

// Get the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Handle API requests
switch ($request_method) {
    case 'GET':
        try {
            $query = "
                SELECT 
                    timetable.timetable_id,
                    courses.course_name AS courseName,
                    timetable.day,
                    timetable.time,
                    timetable.room,
                    users.first_name AS firstName,
                    users.last_name AS lastName
                FROM 
                    timetable
                INNER JOIN 
                    courses ON timetable.course_id = courses.course_id
                INNER JOIN 
                    users ON timetable.user_id = users.user_id
                INNER JOIN 
                    enrollments ON enrollments.program_id = courses.program_id
                WHERE 
                    enrollments.user_id = ?
            ";
            
            // Prepare and execute the query
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                echo json_encode(["error" => "Failed to prepare query."]);
                exit;
            }

            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch all results as an associative array
            $timetables = $result->fetch_all(MYSQLI_ASSOC);

            // Output the results as JSON
            echo json_encode($timetables);

        } catch (Exception $e) {
            echo json_encode(["error" => "An error occurred: " . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>
