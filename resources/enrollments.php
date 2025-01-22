<?php
require_once '../db.php';

// Get user_id from the query string
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$user_id) {
    echo json_encode(["error" => "No user ID provided."]);
    exit;
}


switch ($request_method) {
    case 'GET':
        if ($user_id) { // Fetch courses for a specific student and program
            $stmt = $conn->prepare("
                SELECT 
                    Courses.course_id, 
                    Courses.course_name, 
                    Programs.program_name 
                FROM 
                    Enrollments 
                INNER JOIN Programs ON Enrollments.program_id = Programs.program_id
                INNER JOIN Courses ON Programs.program_id = Courses.program_id
                WHERE Enrollments.user_id = ?
            ");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $courses = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($courses);
        } else { // Fetch all enrollments (optional logic)
            $result = $conn->query("
                SELECT 
                    Enrollments.enrollment_id, 
                    Enrollments.user_id, 
                    Programs.program_name 
                FROM 
                    Enrollments
                INNER JOIN Programs ON Enrollments.program_id = Programs.program_id
            ");
            $enrollments = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($enrollments);
        }
        break;

    case 'POST': // Create a new enrollment
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("
            INSERT INTO Enrollments (student_id, program_id) 
            VALUES (?, ?)
        ");
        $stmt->bind_param("ii", $data['student_id'], $data['program_id']);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Enrollment created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create enrollment."]);
        }
        break;

    case 'PUT': // Update an enrollment
        if ($id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("
                UPDATE Enrollments 
                SET student_id = ?, program_id = ? 
                WHERE enrollment_id = ?
            ");
            $stmt->bind_param("iii", $data['student_id'], $data['program_id'], $id);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Enrollment updated successfully."]);
            } else {
                echo json_encode(["error" => "Failed to update enrollment."]);
            }
        }
        break;

    case 'DELETE': // Delete an enrollment
        if ($id) {
            $stmt = $conn->prepare("
                DELETE FROM Enrollments 
                WHERE enrollment_id = ?
            ");
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
