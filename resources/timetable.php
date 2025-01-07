<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT t.timetable_id, c.course_name, t.day, t.time, t.room, u.first_name, u.last_name FROM Timetable t JOIN Courses c ON t.course_id = c.course_id JOIN Users u ON t.user_id = u.user_id WHERE t.timetable_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT t.timetable_id, c.course_name, t.day, t.time, t.room, u.first_name, u.last_name FROM Timetable t JOIN Courses c ON t.course_id = c.course_id JOIN Users u ON t.user_id = u.user_id");
            $timetables = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($timetables);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO Timetable (course_id, day, time, room, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $data['course_id'], $data['day'], $data['time'], $data['room'], $data['user_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Timetable entry created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create timetable entry."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE Timetable SET course_id = ?, day = ?, time = ?, room = ?, user_id = ? WHERE timetable_id = ?");
            $stmt->bind_param("isssii", $data['course_id'], $data['day'], $data['time'], $data['room'], $data['user_id'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Timetable entry updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update timetable entry."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM Timetable WHERE timetable_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Timetable entry deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete timetable entry."]);
            }
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>