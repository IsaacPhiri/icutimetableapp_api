<?php

require_once '../db.php';

// Assuming you have a way to get the logged-in lecturer's ID, e.g., from session or token
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

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
        $courses = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($courses);
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>