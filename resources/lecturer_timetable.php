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
            WHERE 
                timetable.user_id = ?
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