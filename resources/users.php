<?php

require_once '../db.php';

// Handle API requests
switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Fetch a single user by ID
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            echo json_encode($user);
        } else {
            // Fetch all users
            $result = $conn->query("SELECT * FROM Users");
            $users = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($users);
        }
        break;

    case 'POST':
        // Insert a new user
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $email = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $role = $data['role'];
        $phone_number = $data['phone_number'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];

        $stmt = $conn->prepare("INSERT INTO Users (username, password, email, first_name, last_name, role, phone_number, date_of_birth, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $password, $email, $first_name, $last_name, $role, $phone_number, $date_of_birth, $address);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "User created successfully."]);
        } else {
            echo json_encode(["error" => "Failed to create user."]);
        }
        break;

    case 'PUT':
        // Update an existing user
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['user_id'];
        $username = $data['username'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);
        $email = $data['email'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $role = $data['role'];
        $phone_number = $data['phone_number'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];

        $stmt = $conn->prepare("UPDATE Users SET username = ?, password = ?, email = ?, first_name = ?, last_name = ?, role = ?, phone_number = ?, date_of_birth = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param("sssssssssi", $username, $password, $email, $first_name, $last_name, $role, $phone_number, $date_of_birth, $address, $id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "User updated successfully."]);
        } else {
            echo json_encode(["error" => "Failed to update user."]);
        }
        break;

    case 'DELETE':
        // Delete an existing user
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "User deleted successfully."]);
        } else {
            echo json_encode(["error" => "Failed to delete user."]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method."]);
        break;
}

$conn->close();
?>
