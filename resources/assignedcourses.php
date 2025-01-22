<?php

require_once '../db.php';

// Get user_id from the query string
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$user_id) {
    echo json_encode(["error" => "No user ID provided."]);
    exit;
}

// Handle API requests
switch ($request_method) {
    case 'GET':
        $stmt = $conn->prepare("
            SELECT 
                courses.course_id,
                courses.course_name,
                programs.program_name
            FROM 
                lecturertocourse
            INNER JOIN 
                courses ON lecturertocourse.course_id = courses.course_id
            INNER JOIN 
                programs ON courses.program_id = programs.program_id
            WHERE 
                lecturertocourse.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        
            if ($result->num_rows > 0) {
                $courses = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($courses);
            } else {
                echo json_encode([]);
            }

            $stmt->close();
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>