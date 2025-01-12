<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT a.assignment_id, u.first_name, u.last_name, c.course_name, a.assigned_at FROM LecturerToCourse a JOIN Users u ON a.user_id = u.user_id JOIN Courses c ON a.course_id = c.course_id WHERE a.assignment_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT a.assignment_id, u.first_name, u.last_name, c.course_name, a.assigned_at FROM LecturerToCourse a JOIN Users u ON a.user_id = u.user_id JOIN Courses c ON a.course_id = c.course_id");
            $assignments = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($assignments);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO LecturerToCourse (user_id, course_id, assigned_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $data['user_id'], $data['course_id'], $data['assigned_at']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Assignment created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create assignment."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE LecturerToCourse SET user_id = ?, course_id = ?, assigned_at = ? WHERE assignment_id = ?");
            $stmt->bind_param("iisi", $data['user_id'], $data['course_id'], $data['assigned_at'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Assignment updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update assignment."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM LecturerToCourse WHERE assignment_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Assignment deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete assignment."]);
            }
        }
        break;
    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}
?>