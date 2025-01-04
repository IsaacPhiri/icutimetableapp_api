<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM Enrollments WHERE enrollment_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            print_r($result);
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM Enrollments");
            $enrollments = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($enrollments);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO Enrollments (student_id, program_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['student_id'], $data['program_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Enrollment created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create enrollment."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE Enrollments SET student_id = ?, program_id = ? WHERE enrollment_id = ?");
            $stmt->bind_param("iii", $data['student_id'], $data['program_id'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Enrollment updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update enrollment."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM Enrollments WHERE enrollment_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Enrollment deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete enrollment."]);
            }
        }
        break;
}
?>
