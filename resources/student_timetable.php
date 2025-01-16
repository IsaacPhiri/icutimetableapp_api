<?php

require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT 
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
    enrollments.user_id = ?;");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT 
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
    enrollments.user_id = 3;");
            $timetables = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($timetables);
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>