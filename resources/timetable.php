<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM Timetable WHERE timetable_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM Timetable");
            $timetables = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($timetables);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO Timetable (course_id, lecturer_id, day, start_time, end_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $data['course_id'], $data['lecturer_id'], $data['day'], $data['start_time'], $data['end_time']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Timetable created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create timetable."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE Timetable SET course_id = ?, lecturer_id = ?, day = ?, start_time = ?, end_time = ? WHERE timetable_id = ?");
            $stmt->bind_param("iisssi", $data['course_id'], $data['lecturer_id'], $data['day'], $data['start_time'], $data['end_time'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Timetable updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update timetable."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM Timetable WHERE timetable_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Timetable deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete timetable."]);
            }
        }
        break;
}
?>
