<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM Courses WHERE course_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            print_r($result);
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM Courses");
            $courses = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($courses);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO Courses (course_name, course_code, program_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $data['course_name'], $data['course_code'], $data['program_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Course created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create course."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE Courses SET course_name = ?, course_code = ?, program_id = ? WHERE course_id = ?");
            $stmt->bind_param("ssii", $data['course_name'], $data['course_code'], $data['program_id'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Course updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update course."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM Courses WHERE course_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Course deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete course."]);
            }
        }
        break;
}
?>
