<?php
require_once '../db.php';

switch ($request_method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM Programs WHERE program_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM Programs");
            $programs = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($programs);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO Programs (program_name) VALUES (?)");
        $stmt->bind_param("s", $data['program_name']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Program created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create program."]);
        }
        break;
    case 'PUT':
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("UPDATE Programs SET program_name = ? WHERE program_id = ?");
            $stmt->bind_param("si", $data['program_name'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Program updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update program."]);
            }
        }
        break;
    case 'DELETE':
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM Programs WHERE program_id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Program deleted successfully."]);
            } else {
                echo json_encode(["error" => "Failed to delete program."]);
            }
        }
        break;
}
?>
