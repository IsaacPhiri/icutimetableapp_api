<?php

require_once '../db.php';

// Assuming you have a way to get the logged-in user's ID, e.g., from session or token
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
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $timetables = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($timetables);
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>